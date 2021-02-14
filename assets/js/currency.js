(function ($) {
    $(function () {
        var currency = [
            { id: "AUD", text: "Australian dollar" },
            { id: "BRL", text: "Brazilian real" },
            { id: "CAD", text: "Canadian dollar" },
            { id: "CNY", text: "Chinese yuan" },
            { id: "CZK", text: "Czech koruna" },
            { id: "DKK", text: "Danish krone" },
            { id: "EUR", text: "EURO" },
            { id: "HKD", text: "Hong Kong dollar" },
            { id: "HUF", text: "Hungarian forint" },
            { id: "INR", text: "Indian rupee" },
            { id: "ILS", text: "Israeli new shekel" },
            { id: "JPY", text: "Japanese yen" },
            { id: "MYR", text: "Malaysian ringgit" },
            { id: "MXN", text: "Mexican peso" },
            { id: "TWD", text: "New Taiwan dollar" },
            { id: "NZD", text: "New Zealand dollar" },
            { id: "NOK", text: "Norwegian krone" },
            { id: "PHP", text: "Philippine peso" },
            { id: "PLN", text: "Polish złoty" },
            { id: "GBP", text: "Pound sterling" },
            { id: "RUB", text: "Russian ruble" },
            { id: "SEK", text: "Swedish krona" },
            { id: "SGD", text: "Singapore dollar" },
            { id: "CHF", text: "Swiss franc" },
            { id: "THB", text: "Thai baht" },
            { id: "USD", text: "United States dollar" }

        ]
        function formatCurrency(currency) {
            if (!currency.id) { return currency.text; }
            var currencyData = $(
                '<span class="flag-icon flag-icon-' + currency.id.toLowerCase() + ' flag-icon-squared"></span>' +
                '<span class="flag-text">' + currency.text + "</span>"
            );
            return currencyData;
        };

        //Assuming you have a select element with name country
        // e.g. <select name="name"></select>

        $("[name='currency']").select2({
            placeholder: "---Select currency---",
            templateResult: formatCurrency,
            data: currency
        });


    });
})(jQuery);   