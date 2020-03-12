        var productData = [];
        productData = JSON.parse(localStorage.getItem("products"));
        require(['jquery','mage/url'], function ($, url) {
            jQuery(document).ready(function () {
                function drawTable() {
                    var result = JSON.parse(localStorage.getItem('products'));
                    jQuery('#tabledata').empty();
                    jQuery.each(result, function (key, value) {
                        jQuery("#tabledata").append('<tr><td>' + value.name + '</td><td>' + value.sku + '</td><td>' + value.qty + '</td></tr>');
                    });
                }

                drawTable();
                var data = '';
                jQuery("#load_product").submit(function (e) {
                    e.preventDefault();
                    var getProduct = jQuery("#get_product").val();
                    var linkUrl = url.build('/viewproduct/loadproduct/index');
                    jQuery.ajax({
                        url: linkUrl,
                        type: "POST",
                        data: {get_product: getProduct},
                        showLoader: true,
                        cache: false,
                        success: function (response) {
                            data = response;
                            if (!jQuery.trim(response)) {
                                alert("Product is not configurable");
                                location.reload();
                            } else {
                                jQuery("#newProduct").html('');
                                jQuery("#example").css('visibility', 'visible');
                                jQuery('.productName').html('');
                                jQuery('.productName').html(response.name);
                                jQuery('.productPrice').html('');
                                jQuery('.productPrice').html(response.price + '$');
                                jQuery.each(response.config, function (index, value) {
                                    var select = value.label + '<select class="configurable"  name="' + index + '" >';
                                    jQuery.each(value.values, function (key, opt) {
                                        select += '<option value="' + opt.value_index + '">' + opt.label + '</option>>'
                                    });
                                    select += '</select>';
                                    jQuery("#newProduct").append(select);
                                });
                            }
                        }
                    });
                });
                jQuery("#addbutton").click(function () {
                    var sku = jQuery('#get_product').val();
                    var getQuantity = jQuery("input[name='qty']").val();
                    var productData2 = [];
                    var arr2 = [];
                    var arr = {};
                    jQuery('.configurable').each(function () {
                        arr[jQuery(this).attr('name')] = jQuery(this).val();
                        arr2.push(jQuery(this).find('option:selected').text());
                    });
                    arr2 = arr2.reverse();
                    var i = 0;
                    jQuery.each(arr2, function () {
                        sku += '-' + arr2[i];
                        i++;
                    });
                    var totalStock = '';
                    var linkUr = url.build('/viewproduct/loadproduct/stockcheck');
                    jQuery.ajax({
                        url: linkUr,
                        type: "POST",
                        data: {stock_check: sku},
                        showLoader: true,
                        cache: false,
                        success: function (response) {
                            totalStock = response;
                        }
                    });
                    productData2.push({
                        'qty': getQuantity,
                        'name': data.name,
                        'sku': sku,
                        'id': data.id,
                        'super_attribute': arr,
                        'totalStock': totalStock
                    });
                    if (productData == null) {
                        localStorage.setItem("products", JSON.stringify(productData2));
                        productData = JSON.parse(localStorage.getItem("products"));
                    } else {
                        productData = productData.concat(productData2);
                        localStorage.setItem("products", JSON.stringify(productData));
                    }
                    drawTable();
                });
            });
        });
        require(['jquery', 'mage/url'], function ($, url) {
            jQuery(document).ready(function () {
                jQuery("#addtocart").click(function () {
                    var linkUrl = url.build('/viewproduct/loadproduct/cartadd');
                    jQuery.ajax({
                        url: linkUrl,
                        type: "POST",
                        data: {add_tocart: productData},
                        showLoader: true,
                        cache: false,
                        success: function (response) {
                        }
                    });
                    location.reload();
                });
            });
        });

