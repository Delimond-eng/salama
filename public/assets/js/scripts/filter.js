new Vue({
    el: "#App",

    data() {
        return {
            filter: {
                agent_id: "",
                site_id: "",
                year: new Date().getFullYear(),
                date_begin: "",
                date_end: "",
                period: "",
            },
            isLoading: false,
            search: "",
        };
    },

    mounted() {
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }
        if ($(".tom-select").length) {
            const self = this;
            $(".tom-select").each(function () {
                const placeholder = $(this).data("placeholder"); // ou this.dataset.placeholder
                const tom = new TomSelect(this, {
                    plugins: {
                        dropdown_input: {},
                    },
                    create: false,
                    placeholder: placeholder,
                });

                tom.on("change", function (value) {
                    console.log("Agent selected : ", value);
                });
            });
        }
    },

    methods: {
        triggerFilter() {
            this.isLoading = true;

            setTimeout(() => {
                this.isLoading = false;
            }, 2000);
        },
    },
});
