/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

import {OXD_INPUT_ELEMENTS, OXD_ELEMENTS, OXD_TOASTS} from './oxd';

Cypress.Commands.add('login', ({username, password}) => {
  cy.visit('/auth/login');
  cy.get('input[name=username]').setValue(username);
  cy.get('input[name=password]').setValue(password);
  cy.get('form').submit();
});

Cypress.Commands.add(
  'loginTo',
  ({username, password}, url, options = undefined) => {
    cy.visit(url, options);
    cy.get('input[name=username]').setValue(username);
    cy.get('input[name=password]').setValue(password);
    cy.get('form').submit();
  },
);

Cypress.Commands.add(
  'apiLogin',
  ({username, password}, url = '/auth/login') => {
    cy.visit(url);
    cy.get('input[name=_token]').then(($token) => {
      const csrfToken = $token.val();
      cy.request({
        method: 'POST',
        url: '/index.php/auth/validate',
        form: true,
        body: {
          username,
          password,
          _token: csrfToken,
        },
      });
    });
    // getCookie code was added to support session migration added in OHRM5X-666
    // After login, session id will be changed
    // This results in two cookies appearing during cypress testing
    // If not handled, Session Expiration error will be displayed during tests
    // For the fix, need to clear cookies and set only the new cookie
    // The second cookie in the array contains the older session
    // Note that this behaviour may change if cookie lifetime is set
    // Clear all cookies and set cookies[0] from the array as the only cookie
    cy.getCookies()
      .should('have.length', 2)
      .then((cookies) => {
        cy.clearCookies();
        cy.setCookie('_orangehrm', cookies[0].value, cookies[0]);
      });
  },
);

Cypress.Commands.add('getOXD', (type, options = {}) => {
  return cy.get(OXD_ELEMENTS[type], options);
});

Cypress.Commands.add('getOXDInput', (label) => {
  let element;
  let count;

  const log = Cypress.log({
    autoEnd: false,
    name: 'getOXDInput',
    displayName: 'get oxd input',
    consoleProps() {
      return {
        label: label,
        yielded: element,
        elements: count,
      };
    },
  });

  cy.get(OXD_ELEMENTS.inputGroup, {log: false})
    .contains(label, {log: false})
    .closest(OXD_ELEMENTS.inputGroup, {log: false})
    .find(Object.values(OXD_INPUT_ELEMENTS).join(', '), {log: false})
    .then(($el) => {
      element = Cypress.dom.getElements($el);
      count = $el.length;
      log.set({$el});
      log.snapshot().end();
    });

  cy.on('fail', (err) => {
    log.error(err);
    log.end();
    throw err;
  });
});

Cypress.Commands.add('toast', (type, message, options = {}) => {
  return cy.get(OXD_TOASTS[type], options).contains(message);
});

Cypress.Commands.add(
  'setValue',
  {
    prevSubject: 'element',
  },
  (subject, value) => {
    const element = subject[0];

    const inputEvent = new Event('input', {bubbles: true});

    const setter =
      element.tagName.toLowerCase() === 'input'
        ? Object.getOwnPropertyDescriptor(
            window.HTMLInputElement.prototype,
            'value',
          ).set
        : Object.getOwnPropertyDescriptor(
            window.HTMLTextAreaElement.prototype,
            'value',
          ).set;
    setter && setter.call(element, value);
    element.dispatchEvent(inputEvent);

    Cypress.log({
      name: 'setValue',
      displayName: 'set value',
      message: value,
      $el: subject,
      consoleProps: () => {
        return {
          value,
        };
      },
    });

    cy.wrap(element, {log: false});
  },
);

Cypress.Commands.add(
  'isInvalid',
  {
    prevSubject: 'element',
  },
  (subject, message) => {
    let element;
    let count;

    const log = Cypress.log({
      autoEnd: false,
      name: 'isInvalid',
      displayName: 'is invalid',
      consoleProps() {
        return {
          message: message,
          yielded: element,
          elements: count,
        };
      },
    });

    cy.get(subject, {log: false})
      .closest(OXD_ELEMENTS.inputGroup, {log: false})
      .contains(message, {log: false})
      .then(($el) => {
        element = Cypress.dom.getElements($el);
        count = $el.length;
        log.set({$el});
        log.snapshot().end();
      });

    cy.on('fail', (err) => {
      log.error(err);
      log.end();
      throw err;
    });
  },
);
