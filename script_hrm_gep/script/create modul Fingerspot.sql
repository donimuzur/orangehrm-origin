-- *** SqlDbx Personal Edition ***
-- !!! Not licensed for commercial use beyound 90 days evaluation period !!!
-- For version limitations please check http://www.sqldbx.com/personal_edition.htm
-- Number of queries executed: 1834, number of rows retrieved: 534464

INSERT INTO hs_hr_module (mod_id, name, owner, owner_email, version, description)
  SELECT 'MODFINGERSPOT', 'Fingerspot', 'PT GUNUNG EMAS PUTIH', 'info@geptambang.co.id', 'VER001', 'Fingerspot Integration' 
FROM hs_hr_module WHERE NOT EXISTS 
  (SELECT mod_id FROM hs_hr_module WHERE mod_id ='MODFINGERSPOT')  LIMIT 1;

INSERT INTO ohrm_module (name, status, display_name)
  SELECT 'fingerspot', 1, 'Fingerspot' FROM ohrm_module
WHERE NOT EXISTS 
  (SELECT id FROM ohrm_module WHERE name ='fingerspot')  LIMIT 1;

SELECT id INTO @module_id FROM ohrm_module WHERE name ='fingerspot' ;

INSERT INTO ohrm_module_default_page (module_id, user_role_id, action, enable_class, priority)
  SELECT @module_id,1, 'fingerspot/viewFingerspotAttendanceList',NULL,0 FROM ohrm_module_default_page
WHERE NOT EXISTS 
  (SELECT id FROM ohrm_module_default_page WHERE action ='fingerspot/viewFingerspotAttendanceList' AND module_id =@module_id)  LIMIT 1;
  
INSERT INTO ohrm_data_group (name, description, can_read, can_create, can_update, can_delete)
	SELECT 'apiv2_fingerspot_FingerspotAttendance', 'API-v2 FINGERSPOT - Fingerspot Attendance', 1, 0, 0, 0
FROM ohrm_data_group WHERE NOT EXISTS 
  (SELECT id FROM ohrm_data_group WHERE name ='apiv2_fingerspot_FingerspotAttendance') LIMIT 1;

SELECT id INTO @data_group_id FROM ohrm_data_group WHERE  name ='apiv2_fingerspot_FingerspotAttendance';

INSERT INTO ohrm_api_permission (module_id, data_group_id, api_name)
	SELECT @module_id,@data_group_id, 'OrangeHRM\\Fingerspot\\Api\\FingerspotAttendanceAPI'
FROM ohrm_api_permission WHERE NOT EXISTS 
  (SELECT id FROM ohrm_api_permission WHERE api_name ='OrangeHRM\\Fingerspot\\Api\\FingerspotAttendanceAPI' AND module_id = @module_id) LIMIT 1;

INSERT INTO ohrm_user_role_data_group (user_role_id, data_group_id, can_read, can_create, can_update, can_delete, self)
	SELECT 1, @data_group_id, 1, 1, 1, 1, 0
FROM ohrm_user_role_data_group WHERE NOT EXISTS 
  (SELECT id FROM ohrm_user_role_data_group WHERE user_role_id =1 AND data_group_id = @data_group_id) LIMIT 1;

INSERT INTO ohrm_user_role_data_group (user_role_id, data_group_id, can_read, can_create, can_update, can_delete, self)
	SELECT 2, @data_group_id, 1, 0, 0, 0, 0
FROM ohrm_user_role_data_group WHERE NOT EXISTS 
  (SELECT id FROM ohrm_user_role_data_group WHERE user_role_id =2 AND data_group_id = @data_group_id) LIMIT 1;
 
#//////////////////////////////////////////////////////// 
 #add menu VIEW FINGERSPOT MODULE
 #////////////////////////////////////////////////////////
INSERT INTO ohrm_screen (name, module_id, action_url, menu_configurator)
  SELECT 'View Fingerspot Module', @module_id, 'viewFingerspotModule', NULL
FROM ohrm_screen WHERE NOT EXISTS 
  (SELECT id FROM ohrm_screen WHERE action_url ='viewFingerspotModule' AND module_id =@module_id)  LIMIT 1;
  
SELECT id INTO @screen_id  FROM ohrm_screen WHERE action_url ='viewFingerspotModule' AND module_id =@module_id;

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, `level`, order_hint, status, additional_params)
	SELECT 'Fingerspot', @screen_id, NULL, 1, 100, 1, '{"icon":"time"}' 
FROM ohrm_menu_item WHERE NOT EXISTS 
  (SELECT id FROM ohrm_menu_item WHERE menu_title ='Fingerspot' AND screen_id =@screen_id) LIMIT 1;

INSERT INTO ohrm_user_role_screen (user_role_id, screen_id, can_read, can_create, can_update, can_delete)
	SELECT 1, @screen_id, 1, 1, 1, 1
FROM ohrm_user_role_screen WHERE NOT EXISTS 
  (SELECT id FROM ohrm_user_role_screen WHERE user_role_id =1 AND screen_id = @screen_id) LIMIT 1;

 #//////////////////////////////////////////////////////// 
 #add menu VIEW FINGERSPOT ATTENDANCE LIST
 #////////////////////////////////////////////////////////

INSERT INTO ohrm_screen (name, module_id, action_url, menu_configurator)
  SELECT 'View Fingerspot Attendandce List', @module_id, 'viewFingerspotAttendanceList', NULL
FROM ohrm_screen WHERE NOT EXISTS 
  (SELECT id FROM ohrm_screen WHERE name ='View Fingerspot Attendandce List' AND module_id =@module_id)  LIMIT 1;
 
SELECT id INTO @parent_screen_id  FROM ohrm_menu_item WHERE menu_title ='Fingerspot';
SELECT id INTO @screen_id  FROM ohrm_screen WHERE name ='View Fingerspot Attendandce List' AND module_id =@module_id;

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, `level`, order_hint, status, additional_params)
	SELECT 'Fingerspot Attendance', @screen_id, @parent_screen_id, 2, 200, 1, NULL 
FROM ohrm_menu_item WHERE NOT EXISTS 
  (SELECT id FROM ohrm_menu_item WHERE menu_title ='Fingerspot Attendance' AND screen_id =@screen_id) LIMIT 1;

INSERT INTO ohrm_user_role_screen (user_role_id, screen_id, can_read, can_create, can_update, can_delete)
	SELECT 1, @screen_id, 1, 1, 1, 1
FROM ohrm_user_role_screen WHERE NOT EXISTS 
  (SELECT id FROM ohrm_user_role_screen WHERE user_role_id =1 AND screen_id = @screen_id) LIMIT 1;

