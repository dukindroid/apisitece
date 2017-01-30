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
        customers: {
            columns: [{
                key: "GivenName",
                name: "Given Name",
                template: "linkedGridCell"
            }, {
                key: "Surname",
                name: "Surname"
            }, {
                key: "Email",
                name: "Email",
                width: 33
            }, {
                key: "DateOfBirth",
                name: "Date of Birth",
                filter: {
                    name: "date",
                    args: ["DD MMMM YYYY"]
                }
            }],
            data: [{
                "ID": 0,
                "GivenName": "John",
                "Surname": "Smith",
                "DateOfBirth": "1986-10-03T00:00:00",
                "Email": "john.smith@smithsteel.com",
                "JobTitle": "Co-Founder and CEO",
                "Company": "Smith Steel Pty Ltd"
            }, {
                "ID": 1,
                "GivenName": "Jane",
                "Surname": "Smith",
                "DateOfBirth": "1988-05-28T00:00:00",
                "Email": "jane.smith@smithsteel.com",
                "JobTitle": "Co-Founder and CEO",
                "Company": "Smith Steel Pty Ltd"
            }, {
                "ID": 2,
                "GivenName": "Richard",
                "Surname": "Swanston",
                "DateOfBirth": "1972-08-15T00:00:00",
                "Email": "rswanston@telco.com",
                "JobTitle": "Purchasing Officer",
                "Company": "Cortana Mining Co"
            }, {
                "ID": 3,
                "GivenName": "Robert",
                "Surname": "Brown",
                "DateOfBirth": "1968-01-18T00:00:00",
                "Email": "robbrown@othertelco.com",
                "JobTitle": "Sales Manager",
                "Company": "Powerhouse Marketing"
            }, {
                "ID": 4,
                "GivenName": "Phillip",
                "Surname": "Zucco",
                "DateOfBirth": "1991-06-28T00:00:00",
                "Email": "phil.zucco@workplace.com",
                "JobTitle": "Applications Developer",
                "Company": "Workplace Pty Ltd"
            }, {
                "ID": 5,
                "GivenName": "James",
                "Surname": "Caldwell",
                "DateOfBirth": "1988-07-27T00:00:00",
                "Email": "james.caldwell@random.com",
                "JobTitle": "Purchasing Officer",
                "Company": "Random Industries Ltd."
            }, {
                "ID": 6,
                "GivenName": "Rachael",
                "Surname": "O'Reilly",
                "DateOfBirth": "1972-08-15T00:00:00",
                "Email": "roreilly@energy.com",
                "JobTitle": "Workplace Health and Safety Officer",
                "Company": "Energy Company"
            }]
        }
    }
});