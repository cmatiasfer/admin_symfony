$(document).ready(function () {
    if ($(".__autocomplete")[0]) {
        applyAutocomplete();
    }
});

function applyAutocomplete() {

    $(".__autocomplete[data-path]").each(function () {
        var classList = $(this).attr('class').split(' ');
        var inp_this = $(this).attr('data-contsuggestion');
        $.ajax({
            url: $(this).attr("data-path"),
            success: function (data) {
                _createInputAutocomplete(classList[1], data, inp_this);
            }
        });
        $(this).keyup(function () {
            if ($(this).val() == '') {
                $("[data-elementautocomplete='" + classList[1] + "']").attr('data-id', '');
            }
        });
    });

    // Convierte select en un json.
    $('.convert_options_to_json').each(function () {
        $(this).parent().hide(); // escondo form-group
        var options = $(this).children('option');
        var classAutocomplete = $(this).attr('data-elementautocomplete');
        var json = convertSelectToArrayOfObject(options);
        var inp_this = $(this).attr('data-contsuggestion');

        _createInputAutocomplete(classAutocomplete, json, inp_this);
        setCheckOption(classAutocomplete, options);
    });

    $('.__autocomplete').each(function () {
        var classList = $(this).attr('class').split(' ');

        //Reset EntityType
        $(this).keyup(function () {
            if ($(this).val() == '') {
                $("[data-elementautocomplete='" + classList[1] + "']").children('option').attr('selected', false);
            }
        });
    });
}

function _createInputAutocomplete(classAutocomplete, element, container_suggestion) {
    var section = $("#entity").attr("data-name");
    var mode = $("#entity").attr("data-form");

    $('.' + classAutocomplete).autocomplete({
        lookup: element,
        // lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
        //     console.log("h1!")
        //     console.log(normalize(queryLowerCase))
        //     console.log(suggestion)
        //     return suggestion.value.toLowerCase().indexOf(normalize(queryLowerCase)) !== -1;
        // },
        appendTo: (container_suggestion != '' && container_suggestion) ? $('.' + container_suggestion) : false,
        onSelect: function (suggestion) {
            var id = suggestion.data;
            var text = suggestion.value;
            $(this).attr('data-id', id);

            // Cuando data-path no existe, el input autocomplete esta conectado a EntityType
            // Si el input autocomplete esta conectado a un EntityType
            // busco  la opcion correspondiente dentro del select
            if ($(this).attr('data-path') == undefined) {
                var selectedClass = $('[data-elementautocomplete="' + classAutocomplete + '"]');
                selectedClass.children('option').attr('selected', false);
                selectedClass.children('option[value="' + id + '"]').attr('selected', 'selected');
                if (section == 'products' && !$('tingle-modal-box')[0]) {
                    var listText = text.split("-");
                    $("._sku_brand").val(listText[0]);
                    var skuBrand = $('._sku_brand').val();
                    var skuProduct = $('._sku_product').val();
                    $("#products_skuProduct").val(skuBrand + skuProduct);
                    $('.btn-sku-validate').removeClass('hide');
                }
            }

            if ($('.all_categories')[0]) {
                var id = suggestion.data;
                var text = suggestion.value;

                var exist = findChips("categories", text);

                if (!exist) {
                    var element = {};
                    element.id = id;
                    element.name = text;

                    $(".chips").append(chip(element));
                }
            }
            if ($('.tingle-modal-box').find('.all_colors')[0]) {
                var id = suggestion.data;
                var text = suggestion.value;
                var list = text.split('-');
                var exist = findChips("colors", text);
                if (!exist) {
                    var element = {};
                    element.id = id;
                    element.name = text;
                    element.sku = list[0];
                    $('.tingle-modal-box').find('[data-form="colors"]').children(".chips").append(chip(element));
                }
            }

            if ($('#navbar_value')[0]) {
                $('#navbar_value').val(id + '-' + text);
            }
            if ($('#footer_value')[0]) {
                $('#footer_value').val(id + '-' + text);
            }
        }
    });

    var accentMap = { "á": "a", "é": "e", "í": "i", "ó": "o", "ú": "u" };

    var normalize = function (term) {
        var returnNormalize = "";
        for (var i = 0; i < term.length; i++) {
            returnNormalize += accentMap[term.charAt(i)] || term.charAt(i);
        }
        return returnNormalize;
    };
}



function setCheckOption(classAutocomplete, options) {
    var section = $("#entity").attr("data-name");
    options.each(function (k, v) {
        var itemSelected = $(this).attr('selected');
        if (itemSelected) {
            var id = $(this).val();
            var text = $(this).text();
            $('.' + classAutocomplete).val(text);
            if (section == 'products') {
                var listText = text.split("-");
                $("._sku_brand").val(listText[0]);
            }
        }
    });
}

function convertSelectToArrayOfObject(options) {
    var list = [];

    options.each(function (k, v) {
        var element = {};
        element.data = $(this).attr('value');
        element.value = $(this).text();
        list.push(element);
    });
    return list;
}

/**
 * @param {object } data
 */
function chip(data) {
    var html = "<span class='chip'";
    $.each(data, function (key, val) {
        if (!val || val == 'null') {
            val = '*';
        }
        html += "data-" + key + "='" + val + "'";
    })
    html += ">" + data.name + "<i class='fa fa-times remove-chip'></i> </span>";
    return html;
}

function findChips(nameForm, nameItem) {
    if (nameForm != "categories") {
        var listChips = $(".form-products-variants[data-form='" + nameForm + "'] .chip");
    } else {
        var listChips = $(".chips .chip");
    }
    var exist = false;
    var item = nameItem.toUpperCase();

    listChips.each(function (index, value) {
        var chipText = $(this).text().trim().toUpperCase();
        if (chipText.trim().toUpperCase() == item) {
            exist = true
        }
    });
    return exist;
}