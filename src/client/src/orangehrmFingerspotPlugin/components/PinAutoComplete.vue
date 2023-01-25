
 <template>
    <oxd-input-field
      type="autocomplete"
      :label="$t('Employee Name')"
      :clear="false"
      :create-options="loadEmployees"
    >
      <template #option="{data}">
        <span>{{ data.label }}</span>
      </template>
    </oxd-input-field>
  </template>
  
  <script>
  import {APIService} from '@ohrm/core/util/services/api.service';
  export default {
    name: 'EmployeeAutocomplete',
    props: {
      params: {
        type: Object,
        default: () => ({}),
      },
      apiPath: {
        type: String,
        default: 'api/v2/pim/employees',
      },
    },
    setup(props) {
      const http = new APIService(window.appGlobal.baseUrl, props.apiPath);
      return {
        http,
      };
    },
    methods: {
      async loadEmployees(serachParam) {
        return new Promise(resolve => {
          if (serachParam.trim()) {
            this.http
              .getAll({
                nameOrId: serachParam.trim(),
                ...this.params,
              })
              .then(({data}) => {
                resolve(
                  data.data.map(employee => {
                    return {
                      id: employee.empNumber,
                      label: `${employee.firstName} ${employee.middleName} ${employee.lastName}`,
                      _employee: employee,
                    };
                  }),
                );
              });
          } else {
            resolve([]);
          }
        });
      },
    },
  };
  </script>
  
  <style scoped>
  .past-employee-tag {
    margin-left: auto;
  }
  </style>
  