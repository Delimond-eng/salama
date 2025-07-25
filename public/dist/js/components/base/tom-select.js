(() => {
    (function () {
        "use strict";
        $(".tom-select").each(function () {
            let e = { plugins: { dropdown_input: {} } };
            $(this).data("placeholder") &&
                (e.placeholder = $(this).data("placeholder")),
                $(this).attr("multiple") !== void 0 &&
                    (e = {
                        ...e,
                        plugins: {
                            ...e.plugins,
                            remove_button: { title: "Remove this item" },
                        },
                        persist: !1,
                        create: !0,
                        onDelete: function (t) {
                            return confirm(
                                t.length > 1
                                    ? "Are you sure you want to remove these " +
                                          t.length +
                                          " items?"
                                    : 'Are you sure you want to remove "' +
                                          t[0] +
                                          '"?'
                            );
                        },
                    }),
                $(this).data("header") &&
                    (e = {
                        ...e,
                        plugins: {
                            ...e.plugins,
                            dropdown_header: { title: $(this).data("header") },
                        },
                    }),
                new TomSelect(this, e);
        });
    })();
})();
