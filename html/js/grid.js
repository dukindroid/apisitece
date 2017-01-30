Vue.filter("groupBy", function(value, key) {
    var groups = {
        data: value
    };

    if (key) {
        groups = {};
        for (var i = 0; i < value.length; i++) {
            var row = value[i];
            var cell = row[key];

            if (!groups.hasOwnProperty(cell)) {
                groups[cell] = [];
            }

            groups[cell].push(row);
        }

    }
    return groups;
});

Vue.filter("date", function(value, format) {
    var date = moment(value);

    if (!date.isValid()) {
        return value;
    }

    return date.format(format);
});

Vue.component("dropdown", {
    template: "#dropdown-template",
    props: {

        for: {
            type: String,
            required: true
        },

        origin: {
            type: String,
            default: "top right"
        },

        preserveState: {
            type: Boolean,
            default: false
        }

    },
    computed: {

        originClass: function() {
            switch (this.origin) {
                case "top left":
                    return "dropdown-top-left";
                case "bottom left":
                    return "dropdown-bottom-left";
                case "bottom right":
                    return "dropdown-bottom-right";
            }
        }

    },
    data: function() {
        return {
            show: false
        };
    },
    ready: function() {
        var _this = this;

        var element = document.getElementById(_this.for);

        var hide = function(event) {
            event.stopPropagation();

            if (!(_this.preserveState && _this.$el.contains(event.target))) {
                _this.show = false;
                document.body.removeEventListener("click", hide);
            }

        };

        var show = function(event) {
            event.preventDefault();
            event.stopPropagation();

            var dropdowns = [].slice.call(document.querySelectorAll(".dropdown"));

            dropdowns.forEach(function(dropdown) {
                dropdown.__vue__.show = false;
            });

            if (!_this.show) {
                _this.show = true;

                document.body.addEventListener("click", hide);
            }
        };

        element.addEventListener("click", show);
    }
});

Vue.component("datagridOptions", {
    template: "#datagrid-options-template",
    props: {

        gridId: {
            type: String,
            required: true
        },

        columns: {
            type: Array,
            required: true
        },

        allowSelection: {
            type: Boolean
        },

        allowEdit: {
            type: Boolean
        },

        groupingColumn: {
            type: Object,
            required: true
        },

        dataFilter: {
            type: String,
            required: true
        },

        showAdvancedOptions: {
            type: Boolean
        }

    },
    methods: {

        getControlName(columnKey, suffix) {
            return this.gridId + "-" + columnKey + "-" + suffix;
        }

    }
});

Vue.component("datagrid", {
    template: "#datagrid-template",
    components: ["datagridOptions"],
    props: {

        id: {
            type: String,
            required: true
        },

        columns: {
            type: Array,
            required: true
        },

        data: {
            type: Array
        },

        cellTemplate: {
            type: String,
            required: false,
            default: "defaultGridCell"
        },

        allowSelection: {
            type: Boolean,
            required: false,
            default: false
        },

        allowEdit: {
            type: Boolean,
            required: false,
            default: false
        },

        showDefaultOptions: {
            type: Boolean,
            required: false,
            default: true
        },

        showAdvancedOptions: {
            type: Boolean,
            required: false,
            default: false
        }

    },
    computed: {

        columnSpan: function() {
            return this.allowSelection ? this.columns.length + 1 : this.columns.length;
        },

        showOptions: function() {
            return this.showDefaultOptions || this.showAdvancedOptions;
        },

        showFooter: function() {
            return this.dataFilter || this.groupingColumn || this.selectedRows.length > 0;
        }

    },
    data: function() {

        return {
            sortingKey: null,
            sortingDirection: 1,
            groupingColumn: null,
            dataFilter: null,
            selectedRows: [],
            selectAll: false
        };

    },
    methods: {

        getCellTemplate: function(column) {
            return this.allowEdit ? "editableGridCell" : (column.template || this.cellTemplate);
        },

        getCellWidth: function(column) {
            if (!column.width) {
                return;
            }

            return column.width + (isNaN(column.width) ? "" : "%");
        },

        getControlId: function(groupName, index, suffix) {
            return groupName + "-" + index + (suffix ? "-" + suffix : "");
        },

        sortBy: function(column) {
            if (column.key === this.sortingKey) {
                this.sortingDirection *= -1;
                return;
            }

            this.sortingKey = column.key;
            this.sortingDirection = 1;
        },

        groupBy: function(column) {
            this.groupingColumn = column;
        },

        resetFilter() {
            this.dataFilter = null;
        },

        resetGrouping() {
            this.groupingColumn = null;
        },

        resetSelection() {
            this.selectedRows = [];
            this.selectAll = false;
        },

        formatData: function(column, value) {
            if (column.hasOwnProperty("filter")) {
                var filter = Vue.filter(column.filter.name);
                var args = [].concat(value, column.filter.args);
                return filter.apply(this, args);
            }
            return value;
        }
    },
    watch: {

        "selectAll": function(value) {
            this.selectedRows = value ? [].concat(this.data) : [];
        }

    }
});

Vue.partial("defaultGridCell", "<span>{{ formatData(column, row[column.key]) }}</span>");
Vue.partial("editableGridCell", "<input type=\"text\" v-model=\"row[column.key]\" lazy/>");
Vue.partial("linkedGridCell", "<a href=\"http://www.google.com?q={{ row.GivenName }}\"><partial name=\"defaultGridCell\"></partial></a>");

var vue = new Vue({
    el: "#index",
    data: {
        gastos: {
            columns: [{
                key: "clave",
                name: "Clave",
            }, {
                key: "desc",
                name: "Descripción",
            }, {
                key: "monto",
                name: "Monto",
            }], 
            data: [{
                "ID": 0,
                "clave" : "CA-104",
                "desc" : "Arrendadora",
                "monto" : "12000"
            }, {
                "ID": 1,
                "clave" : "CA-106",
                "desc" : "Impuestos Federales",
                "monto" : "20000"
            }, {
                "ID": 2,
                "clave" : "CA-106-1",
                "desc" : "Impuesto 2% de Nomina",
                "monto" : "550"
            }, {
                "ID": 3,
                "clave" : "CA-107",
                "desc" : "Fin de Año (Posada)",
                "monto" : "7500"
            }, {
                "ID": 4,
                "clave" : "CA-110",
                "desc" : "Fechas Esp. Día del Estudiante",
                "monto" : "2500"
            }, {
                "ID": 5,
                "clave" : "CA-111",
                "desc" : "Tramites Ayuntamiento",
                "monto" : "1000"
            }, {
                "ID": 6,
                "clave" : "CA-114",
                "desc" : "Reposición de Caja Chica",
                "monto" : "3000"
            }, {
                "ID": 7,
                "clave" : "CA-201",
                "desc" : "Nomina Administracion",
                "monto" : "27500"
            }, {
                "ID": 8,
                "clave" : "CA-201-1",
                "desc" : "Nomina Profesores",
                "monto" : "70000"
            }, {
                "ID": 9,
                "clave" : "CA-202",
                "desc" : "Aguinaldo Administracion",
                "monto" : "7000"
            }, {
                "ID": 10,
                "clave" : "CA-205",
                "desc" : "Imss",
                "monto" : "6500"
            }, {
                "ID": 11,
                "clave" : "CA-206",
                "desc" : "Infonavit",
                "monto" : "4800"
            }, {
                "ID": 12,
                "clave" : "CA-208-1",
                "desc" : "Honorarios Contabilidad",
                "monto" : "2800"
            }, {
                "ID": 13,
                "clave" : "CA-302",
                "desc" : "Publicidad",
                "monto" : "20000"
            }, {
                "ID": 14,
                "clave" : "CA-305",
                "desc" : "Articulos de Limpieza",
                "monto" : "2500"
            }, {
                "ID": 15,
                "clave" : "CA-306",
                "desc" : "Luz CFE",
                "monto" : "12000"
            }, {
                "ID": 16,
                "clave" : "CA-307",
                "desc" : "Papeleria",
                "monto" : "3000"
            }, {
                "ID": 17,
                "clave" : "CA-308",
                "desc" : "Tintas y Recarga de Toner",
                "monto" : "1000"
            }, {
                "ID": 18,
                "clave" : "CA-311",
                "desc" : "Renta Edificio Escuela",
                "monto" : "24000"
            }, {
                "ID": 19,
                "clave" : "CA-314",
                "desc" : "Teléfono",
                "monto" : "3000"
            }, {
                "ID": 20,
                "clave" : "CA-315",
                "desc" : "Uniformes del Personal",
                "monto" : "3000"
            }, {
                "ID": 21,
                "clave" : "CA-318",
                "desc" : "Viáticos Ventas",
                "monto" : "1800"
            }, {
                "ID": 22,
                "clave" : "CA-401",
                "desc" : "Mantenimiento Edificio",
                "monto" : "2000"
            }, {
                "ID": 23,
                "clave" : "CA-404",
                "desc" : "Compra Equipo de computo",
                "monto" : "30000"
            }, {
                "ID": 24,
                "clave" : "CA-503",
                "desc" : "Comisiones Bancarias",
                "monto" : "1000"
            }, {
                "ID": 25,
                "clave" : "CU-RP04",
                "desc" : "Salario Asesor",
                "monto" : "4800"
            }, {
                "ID": 26,
                "clave" : "CU-RP05",
                "desc" : "Salario Gerente",
                "monto" : "8000"
            }, {
                "ID": 27,
                "clave" : "CU-RP11",
                "desc" : "Complemento de Comisiones",
                "monto" : "3000"
            }, {
                "ID": 28,
                "clave" : "CU-RP10",
                "desc" : "Aguinaldo Ventas",
                "monto" : "6000"
            }, {
                "ID": 29,
                "clave" : "CU-AD02",
                "desc" : "Renta del Local Escuela",
                "monto" : "30000"
            }, {
                "ID": 30,
                "clave" : "CU-AD03",
                "desc" : "Rentas Estacionamiento",
                "monto" : "7000"
            }, {
                "ID": 31,
                "clave" : "CU-AD05",
                "desc" : "Nomina Profes. Prepa",
                "monto" : "30000"
            }, {
                "ID": 32,
                "clave" : "CU-AD05-3",
                "desc" : "Nomina Administración",
                "monto" : "35100"
            }, {
                "ID": 33,
                "clave" : "CU-AD09",
                "desc" : "Aguinaldo Adminsitración",
                "monto" : "8000"
            }, {
                "ID": 34,
                "clave" : "CU-AD10",
                "desc" : "Viáticos Administración",
                "monto" : "1000"
            }, {
                "ID": 35,
                "clave" : "CU-AD12",
                "desc" : "Mantenimiento Edificio",
                "monto" : "1000"
            }, {
                "ID": 36,
                "clave" : "CU-AD14",
                "desc" : "Agua del Edificio Escuela",
                "monto" : "4000"
            }, {
                "ID": 37,
                "clave" : "CU-AD18",
                "desc" : "Telefono",
                "monto" : "1000"
            }, {
                "ID": 38,
                "clave" : "CU-AD23",
                "desc" : "Pago Sep Certificado",
                "monto" : "2000"
            }]
        }
    }
});
