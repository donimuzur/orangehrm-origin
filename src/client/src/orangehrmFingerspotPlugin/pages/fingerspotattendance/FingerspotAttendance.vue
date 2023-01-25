<template>
  <div class="orangehrm-background-container">
    <oxd-table-filter :filter-title="$t('Fingerspot Attendance List')">
      <oxd-form @submitValid="filterItems" @reset="filterItems">
        <oxd-form-row>
          <oxd-grid :cols="4" class="orangehrm-full-width-grid">
             <oxd-grid-item>
              <date-input
                v-model="filters.scanDate"
                :label="$t('Scan Date')"
                required
              />
             </oxd-grid-item>
          </oxd-grid>
          <oxd-grid :cols="4" class="orangehrm-full-width-grid">
            <oxd-grid-item>
            <employee-autocomplete
                v-model="filters.pin"
                :rules="rules.employee"
                :params="{
                  includeEmployees: filters.includeEmployees?.param,
                }"
                required
              />
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>

        <oxd-divider />
        <oxd-form-actions>
          <oxd-button
            display-type="ghost"
            :label="$t('Reset')"
            type="reset"
          />
          <oxd-button
            class="orangehrm-left-space"
            display-type="secondary"
            :label="$t('Search')"
            type="submit"
          />
        </oxd-form-actions>
      </oxd-form>
    </oxd-table-filter>
    <br />
    <div class="orangehrm-paper-container">
      <!-- <div
        v-if="$can.create('fingerspot_list')"
        class="orangehrm-header-container"
      >
      <oxd-button
          :label="$t('Add')"
          icon-name="plus"
          display-type="secondary"
          @click="onClickAdd"
        />
      </div>
      <table-header
        :selected="checkedItems.length"
        :total="total"
        :loading="isLoading"
        @delete="onClickDeleteSelected"
      ></table-header>
      <div class="orangehrm-container">
        <oxd-card-table
          ref="cardTable"
          v-model:selected="checkedItems"
          v-model:order="sortDefinition"
          :headers="headers"
          :items="items?.data"
          :selectable="$can.delete('fingerspotattendance_list')"
          :clickable="true"
          :loading="isLoading"
          class="orangehrm-employee-list"
          row-decorator="oxd-table-decorator-card"
          @click="onClickEdit"
        />
      </div>
      <div class="orangehrm-bottom-container">
        <oxd-pagination
          v-if="showPaginator"
          v-model:current="currentPage"
          :length="pages"
        />
      </div> -->
    </div>
    <delete-confirmation ref="deleteDialog"></delete-confirmation>
  </div>
</template>

<script >
import {computed, ref} from 'vue';
import DeleteConfirmationDialog from '@ohrm/components/dialogs/DeleteConfirmationDialog';
import usePaginate from '@ohrm/core/util/composable/usePaginate';
import {navigate} from '@ohrm/core/util/helper/navigation';
import {APIService} from '@/core/util/services/api.service';
// import PinAutocomplete from '@/core/components/inputs/EmployeeAutocomplete';
import EmployeeAutocomplete from '@/orangehrmFingerspotPlugin/components/PinAutoComplete';
import useSort from '@ohrm/core/util/composable/useSort';
import {validSelection} from '@/core/util/validation/rules';
import usei18n from '@/core/util/composable/usei18n';

const defaultSortOrder = {
  'fingerspotAttendance.pin': 'ASC',
  'fingerspotAttendance.scanDate': 'DESC'
};

export default {
  components: {
    'delete-confirmation': DeleteConfirmationDialog,
    'employee-autocomplete': EmployeeAutocomplete,
    // 'pin-autocomplete': PinAutocomplete,
  },

  props: {
    unselectableEmpNumbers: {
      type: Array,
      default: () => [],
    },
  },

  setup(props) {
    const {$t} = usei18n();
    const dataNormalizer = data => {
      return data.map(item => {
        return {
          pin: item.pin,
          scanDate: item.scanDate,
        };
      });
    };

    const filters = ref({
      pin: '',
      scanDate:null,
    });
    const {sortDefinition, sortField, sortOrder, onSort} = useSort({
      sortDefinition: defaultSortOrder,
    });
    const serializedFilters = computed(() => {
      return {
        model: 'default',
        pin: filters.value.pin,
        scanDate: filters.value.scanDate,
        sortField: sortField.value,
        sortOrder: sortOrder.value,
      };
    });

    const http = new APIService(
      window.appGlobal.baseUrl,
      'api/v2/fingerspot/fingerspotAttendance',
    );
    const {
      showPaginator,
      currentPage,
      total,
      pages,
      pageSize,
      response,
      isLoading,
      execQuery,
    } = usePaginate(http, {
      query: serializedFilters,
      normalizer: dataNormalizer,
    });

    // onSort(execQuery);

    return {
      http,
      showPaginator,
      currentPage,
      isLoading,
      total,
      pages,
      pageSize,
      execQuery,
      items: response,
      filters,
      sortDefinition,
    };
  },
  data() {
    return {
      checkedItems: [],
      rules: {
        employee: [],
        supervisor: [validSelection],
      },
    };
  },
  computed: {
    headers() {
      return [
        {
          name: 'pin',
          slot: 'title',
          title: this.$t('Pin'),
          sortField: 'fingerspotAttendance.pin',
          style: {flex: 1},
        },
        {
          name: 'scanDate',
          title: this.$t('Scan Date'),
          sortField: 'fingerspotAttendance.scanDate',
          style: {flex: 1},
        },
        {
          name: 'actions',
          slot: 'action',
          title: this.$t('general.actions'),
          style: {flex: 1},
          cellType: 'oxd-table-cell-actions',
          cellConfig: {
            ...(this.$can.delete('fingerspotattendance_list') && {
              delete: {
                onClick: this.onClickDelete,
                component: 'oxd-icon-button',
                props: {
                  name: 'trash',
                },
              },
            }),
            edit: {
              onClick: this.onClickEdit,
              props: {
                name: 'pencil-fill',
              },
            },
          },
        },
      ];
    },
  },

  methods: {
    // async resetDataTable() {
    //   this.checkedItems = [];
    //   await this.execQuery();
    // },
    async filterItems() {
      await this.execQuery();
    },
  },
};
</script>
