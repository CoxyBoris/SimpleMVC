
// erp.js
// ====================================================================


$(document).ready(function() {

        $("#id_phone_home").intlTelInput();
        $("#id_phone_office").intlTelInput();
        $("#id_phone_mobile").intlTelInput();
        $("#id_fax").intlTelInput();
        
        // MODAL CONFIRM
	// =================================================================        
        $(".open-modalConfirm").click(function () {
            var modalTitle = $(this).data('title');
            var modalDesc = $(this).data('desc');
            var modalHref = $(this).data('href');

            $("#modalConfirm > .modal-dialog > .modal-content > .modal-header > .modal-title").html(modalTitle);    
            $("#modalConfirm > .modal-dialog > .modal-content > .modal-body > p").html( modalDesc );

            if($(this).data('buttons') === 1)
            {
               $("#modalConfirm > .modal-dialog > .modal-content > .modal-footer > a").attr('href', modalHref );
               $("#modalConfirm > .modal-dialog > .modal-content > .modal-footer").show(0);
            }
            else
            {
               $("#modalConfirm > .modal-dialog > .modal-content > .modal-footer").hide(0); 
            }
        });
        
        // MODAL ADD CATEGORY SUPPLY
	// =================================================================        
        $(".open-addsuppliescategory").click(function () {
            var modalselected = $(this).data('selected');

            $("#id_id_selected_cat").val( modalselected );
            
            var modalmodif = $(this).data('modif');
            if(modalmodif >0)
            {
                $("#id_id_modif_cat").val( modalmodif );
                
                $.ajax({
                    type: "POST",
                    url: "MODEL/AJAX/ajax.modal_modif_category_supply.php",
                    data: { id_category_supply: modalmodif }
                  }).done(function( category_supply_name ) {
                      $("#modal_addsuppliescategory > .modal-dialog > .modal-content > form > .modal-header > .modal-title").html( "Modification d'une catégorie" );
                      $("#id_input_category_name").val(category_supply_name);
                      $("#id_action").val("EDIT_CATEGORY_SUPPLY;"+Math.random());
                  });
            }
        });
        
        // MODAL DELETE CATEGORY SUPPLY
	// =================================================================        
        $(".open-deletesuppliescategory").click(function () {
            var modalselected = $(this).data('selected');

            $("#id_id_selected_cat_del").val( modalselected );
            
            var modaldelete = $(this).data('delete');
            if(modaldelete >0)
            {
                $.ajax({
                    type: "POST",
                    url: "MODEL/AJAX/ajax.modal_delete_category_supply.php",
                    data: { id_category_supply: modaldelete }
                  }).done(function( bsupp ) {
                      if(bsupp === "0")
                      {
                          $("#modal_deletesuppliescategory > .modal-dialog > .modal-content > form > .modal-body > p").html( "Suppression impossible : la catégorie a des filles ou des matières." );
                          $("#id_btn_delete").attr('disabled','disabled');
                      } 
                      else
                      {
                          $("#modal_deletesuppliescategory > .modal-dialog > .modal-content > form > .modal-body > p").html( "Vous êtes sur le point de supprimer cette catégorie" );
                          $("#id_btn_delete").removeAttr('disabled');
                          $("#id_id_delete_cat").val( modaldelete );
                      }
                  });
            }
        });
        
        // MODAL DELETE SUPPLY
	// =================================================================        
        $(".open-deletesupply").click(function () {
            var modalselected = $(this).data('selected');

            $("#id_id_selected_cat_del").val( modalselected );
            
            var modaldelete = $(this).data('delete');
            if(modaldelete >0)
            {
                $.ajax({
                    type: "POST",
                    url: "MODEL/AJAX/ajax.modal_delete_supply.php",
                    data: { id_supply: modaldelete }
                  }).done(function( bsupp ) {
                      if(bsupp === "0")
                      {
                          $("#modal_deletesupply > .modal-dialog > .modal-content > form > .modal-body > p").html( "Suppression impossible : la matière est utilisée dans un modèle et/ou a été commandée." );
                          $("#id_btn_delete").attr('disabled','disabled');
                      } 
                      else
                      {
                          $("#modal_deletesupply > .modal-dialog > .modal-content > form > .modal-body > p").html( "Vous êtes sur le point de supprimer cette matière" );
                          $("#id_btn_delete").removeAttr('disabled');
                          $("#id_id_delete_supply").val( modaldelete );
                      }
                  });
            }
        });        
        
        // MODAL USE SUPPLY
	// =================================================================        
        $(".open-usesupply").click(function () {
            var modalselected = $(this).data('selected');

            var modaluse = $(this).data('use');
            if(modaluse >0)
            {
                $.ajax({
                    type: "POST",
                    url: "MODEL/AJAX/ajax.modal_use_supply.php",
                    data: { id_supply: modaluse },
                    dataType: 'json' 
                  }).done(function( data ) {
                      
                      $("#modal_usesupply > .modal-dialog > .modal-content > .modal-body > p").html('');
                      
                      if(Object.keys(data).length == 0) {
                          $("#modal_usesupply > .modal-dialog > .modal-content > .modal-body > p").html('La matière n\'est utilisé dans aucun modèle');
                      }
                      else {
                        for (var i in data) {
                          $("#modal_usesupply > .modal-dialog > .modal-content > .modal-body > p").append('Modèle: ' + data[i] + '<br/>');
                        }
                      }
                  });
            }
        });   
        
        // COMPANY CONTACT INFO
	// =================================================================        
        $(".open-modal_companyContactInfo").click(function () {
            var modalTitle = $(this).data('title');
            var modalemail = $(this).data('email');
            var modalphone1 = $(this).data('phone1');
            var modalphone2 = $(this).data('phone2');
            var modalmobilephone = $(this).data('mobilephone');
            var modalfax = $(this).data('fax');

            $("#modal_companyContactInfo > .modal-dialog > .modal-content > .modal-header > .modal-title").html(modalTitle);    
            $("#id_email > a").html( modalemail );
            $("#id_email > a").attr( 'href', modalemail );
            $("#id_phone1").html( modalphone1 );
            $("#id_phone2").html( modalphone2 );
            $("#id_mobilephone").html( modalmobilephone );
            $("#id_fax").html( modalfax );

        });
        
        // COMPANY CONTACT EDITION
	// =================================================================        
        $(".open-modal_addcompanycontact").click(function () {
            var modalid_contact = $(this).data('id_contact');
            var modalfirstname = $(this).data('firstname');
            var modalname = $(this).data('name');
            var modalgender = $(this).data('gender');
            var modalfunction = $(this).data('function');
            var modalemail = $(this).data('email');
            var modalphone1 = $(this).data('phone1');
            var modalphone2 = $(this).data('phone2');
            var modalmobilephone = $(this).data('mobilephone');
            var modalfax = $(this).data('fax');

            $("#modal_addcompanycontact > .modal-dialog > .modal-content > form > .modal-header > .modal-title").html("Modification du contact");  
            $("#id_input_id_contact").val( modalid_contact );
            $("#id_input_firstname").val( modalfirstname );
            $("#id_input_name").val( modalname );
            $("#id_select_gender option[value="+modalgender+"]").attr( "selected", "selected" );
            $("#id_select_gender").selectpicker('refresh');
            $("#id_input_function").val( modalfunction );
            $("#id_input_email").val( modalemail );
            $("#id_phone1").val( modalphone1 );
            $("#id_phone2").val( modalphone2 );
            $("#id_mobilephone").val( modalmobilephone );
            $("#id_fax").val( modalfax );
            $("#modal_addcompanycontact > .modal-dialog > .modal-content > form > .modal-footer > .btn-primary").html("Modifier");  

        });
        
        // COMPANY ADDRESS INFO
	// =================================================================        
        $(".open-modal_companyAddressInfo").click(function () {
            var modalTitle = $(this).data('title');
            var modalDesc = $(this).data('desc');
            
            $("#modal_companyAddressInfo > .modal-dialog > .modal-content > .modal-header > .modal-title").html(modalTitle);    
            $("#modal_companyAddressInfo > .modal-dialog > .modal-content > .modal-body > p").html( modalDesc );

        });
        
        // COMPANY ADDRESS EDITION
	// =================================================================        
        $(".open-modal_addcompanyaddress").click(function () {
            var modalid_address = $(this).data('id_address');
            var modalname = $(this).data('addressname');
            var modaladdress1 = $(this).data('address1');
            var modaladdress2 = $(this).data('address2');
            var modalzipcode = $(this).data('zipcode');
            var modalcity = $(this).data('city');
            var modalcountry_iso1 = $(this).data('country_iso1');

            $("#modal_addcompanyaddress > .modal-dialog > .modal-content > form > .modal-header > .modal-title").html("Modification de l'adresse");  
            $("#id_input_id_address").val( modalid_address );
            $("#id_input_addressname").val( modalname );
            $("#id_input_address1").val( modaladdress1 );
            $("#id_input_address2").val( modaladdress2 );
            $("#id_input_zipcode").val( modalzipcode );
            $("#id_input_city").val( modalcity );
            $("#id_select_country option[value="+modalcountry_iso1+"]").attr( "selected", "selected" );
            $("#id_select_country").selectpicker('refresh');
            $("#modal_addcompanyaddress > .modal-dialog > .modal-content > form > .modal-footer > .btn-primary").html("Modifier");  

        });
        
        // ORDER ADD EDIT
	// =================================================================        
        $('input[name=radio_type]').change(function() {
            if($('input[name=radio_type]:checked').val() === "market")
            {
                $("#id_select_market").prop( "disabled", false );
                $("#id_select_customer").prop( "disabled", "disabled" );
                $("#id_select_company").prop( "disabled", "disabled" );
                $("#id_select_market").selectpicker('refresh');
                $("#id_select_customer").selectpicker('refresh');
                $("#id_select_company").selectpicker('refresh');
            }
            else
            {
                $("#id_select_market").prop( "disabled", "disabled" );
                $("#id_select_customer").prop( "disabled", false );
                $("#id_select_company").prop( "disabled", false );
                $("#id_select_market").selectpicker('refresh');
                $("#id_select_customer").selectpicker('refresh');
                $("#id_select_company").selectpicker('refresh');
            }
        });
        
        $('input[name=input_b_multi_delivery]').change(function() {
            if($('input[name=input_b_multi_delivery]').is(':checked'))
            {
                $("#id_input_shipping_date").prop( "disabled", "disabled" );
            }
            else
            {
                $("#id_input_shipping_date").prop( "disabled", false );
            }
          });
        
        
        // MODEL ADD EDIT	
	// =================================================================
	$('#chosen-select-colors').chosen({width:'100%'});
	
        $('#id_select_size_grid').change(function() {
            var sel = $('#id_select_size_grid option:selected').val();
            $('.size_grid_div').hide();
            $('#size_grid_'+sel).show();
        });
        
        // MODAL PURCHASE RECEIPT
	// =================================================================        
        $(".open-purchasereceipt").click(function () {
            var purchase_line_selected = $(this).data('id_purchase_line');

            if(purchase_line_selected >0)
            {
                $("#id_input_id_purchase_line").val( purchase_line_selected );
                
                $.ajax({
                    type: "POST",
                    url: "MODEL/AJAX/ajax.modal_purchase_receipt.php",
                    data: { id_purchase_line: purchase_line_selected }
                  }).done(function( data ) {
                      
                      $("#modal_purchasereceipt > .modal-dialog > .modal-content > .modal-body > p").html(data);
                  });
            }
        }); 
            
        // SUPPLY ADD EDIT	
	// =================================================================
	$('#chosen-select-suppliers').chosen({width:'100%'});
        
        $('#id_select_type').change(function() {
            var sel = $('#id_select_type option:selected').val();
            if(sel === "FAT")
            {
                $('#id_tags_variant').show();
            }
            else
            {
                $('#id_tags_variant').hide();
            }
        });
        
        // INVOICE SUPPLIER ADD EDIT	
	// =================================================================
	$('#id_input_supplier').change(function() {
            var sel = $('#id_input_supplier option:selected').val();
            
            $('.selectallocation option').remove();
            $('.selectorder option').remove();
            
            $('#id_select_allocation_1').append('<option value="0">Aucune</option>');
            $('#id_select_allocation_2').append('<option value="0">Aucune</option>');
            $('#id_select_allocation_3').append('<option value="0">Aucune</option>');
            $('#id_select_allocation_4').append('<option value="0">Aucune</option>');
            $('#id_select_order_1').append('<option value="0">Aucun</option>');
            $('#id_select_order_2').append('<option value="0">Aucun</option>');
            $('#id_select_order_3').append('<option value="0">Aucun</option>');
            $('#id_select_order_4').append('<option value="0">Aucun</option>');
            
            $('.selectallocation').selectpicker('refresh');
            $('.selectorder').selectpicker('refresh');
            
            $.ajax({
                    type: "POST",
                    url: "MODEL/AJAX/ajax.subview_invoice_supplier.php",
                    data: { id_company: sel},
                    dataType: 'json'
                  }).done(function( data ) {
                      
                      var allocations = data["ALLOCATION"];
                      for (var i in allocations) {
                          if(allocations[i].ID !== null)
                          {
                              $('#id_select_allocation_1').append('<option value="'+allocations[i].ID+'">'+allocations[i].CODE+' - '+allocations[i].NAME+'</option>');
                              $('#id_select_allocation_2').append('<option value="'+allocations[i].ID+'">'+allocations[i].CODE+' - '+allocations[i].NAME+'</option>');
                              $('#id_select_allocation_3').append('<option value="'+allocations[i].ID+'">'+allocations[i].CODE+' - '+allocations[i].NAME+'</option>');
                              $('#id_select_allocation_4').append('<option value="'+allocations[i].ID+'">'+allocations[i].CODE+' - '+allocations[i].NAME+'</option>');
                              $('.selectallocation').selectpicker('refresh');
                          }
                      }
                      
                      var orders = data["ORDER"];
                      for (var i in orders) {
                          if(orders[i].ID !== null)
                          {
                              $('#id_select_order_1').append('<option value="'+orders[i].ID+'">'+orders[i].NAME+'</option>');
                              $('#id_select_order_2').append('<option value="'+orders[i].ID+'">'+orders[i].NAME+'</option>');
                              $('#id_select_order_3').append('<option value="'+orders[i].ID+'">'+orders[i].NAME+'</option>');
                              $('#id_select_order_4').append('<option value="'+orders[i].ID+'">'+orders[i].NAME+'</option>');
                              $('.selectorder').selectpicker('refresh');
                          }
                      }
                      
                      $('.taxrate').val(data["TAX_RATE"]);
                  });
        });
        
        $('#id_input_amount_pretax_1').change(function() {
            allocation("1");
        });
        $('#id_input_tax_rate_1').change(function() {
            allocation("1");
        });
        
        $('#id_input_amount_pretax_2').change(function() {
            allocation("2");
        });
        $('#id_input_tax_rate_2').change(function() {
            allocation("2");
        });
        
        $('#id_input_amount_pretax_3').change(function() {
            allocation("3");
        });
        $('#id_input_tax_rate_3').change(function() {
            allocation("3");
        });
        
        $('#id_input_amount_pretax_4').change(function() {
            allocation("4");
        });
        $('#id_input_tax_rate_4').change(function() {
            allocation("4");
        });
        
        function allocation(num)
        {
            var amount = parseFloat($('#id_input_amount_pretax_'+num).val());
            var taxrate = parseFloat($('#id_input_tax_rate_'+num).val());
            var tax = parseFloat((amount*taxrate).toFixed(2));
            
            $('#id_input_amount_tax_'+num).val(tax);
            $('#id_input_amount_taxed_'+num).val(amount+tax);
            
            calculate_invoice_supplier_total();
        }
        
        function calculate_invoice_supplier_total()
        {
            var amount_pretax = parseFloat($('#id_input_amount_pretax_1').val())+parseFloat($('#id_input_amount_pretax_2').val())+parseFloat($('#id_input_amount_pretax_3').val())+parseFloat($('#id_input_amount_pretax_4').val());
            var amount_tax = parseFloat($('#id_input_amount_tax_1').val())+parseFloat($('#id_input_amount_tax_2').val())+parseFloat($('#id_input_amount_tax_3').val())+parseFloat($('#id_input_amount_tax_4').val());
            var amount_taxed = parseFloat($('#id_input_amount_taxed_1').val())+parseFloat($('#id_input_amount_taxed_2').val())+parseFloat($('#id_input_amount_taxed_3').val())+parseFloat($('#id_input_amount_taxed_4').val());
            
            $('#id_input_amount_pretax').val(amount_pretax.toFixed(2));
            $('#id_input_amount_tax').val(amount_tax.toFixed(2));
            $('#id_input_amount_taxed').val(amount_taxed.toFixed(2));
        }
        
        // COMPANY ACCOUNTING EDIT	
	// =================================================================
        $('.chosen-select-accounting').chosen({width:'100%'});
        
        // ORDERS
	// =================================================================
        $('#pie-orders').easyPieChart({
		barColor :'#5fa2dd',
		scaleColor: false,
		trackColor : '#eee',
		lineCap : 'round',
		lineWidth :8,
		onStep: function(from, to, percent) {
			$(this.el).find('.pie-value').text(Math.round(percent) + '%');
		}
	});
        $('#pie-orders-max').easyPieChart({
		barColor :'#5fa2dd',
		scaleColor: false,
		trackColor : '#eee',
		lineCap : 'round',
		lineWidth :8,
		onStep: function(from, to, percent) {
			$(this.el).find('.pie-value').text(Math.round(percent) + '%');
		}
	});
        
        // MODAL EDIT SHIPPING PARTIAL
	// =================================================================        
        /* $(".open-editshippingpartial").click(function () {
            var modalshipping = $(this).data('shipping');
            var modalordermodel = $(this).data('ordermodel');

            $("#id_id_shipping").val( modalshipping );
            $("#id_id_order_model").val( modalordermodel );
            
            
            $.ajax({
                type: "POST",
                url: "MODEL/AJAX/ajax.modal_edit_shipping_partial.php",
                data: { id_shipping_partial: modalshipping }
              }).done(function( category_supply_name ) {
                  $("#modal_addsuppliescategory > .modal-dialog > .modal-content > form > .modal-header > .modal-title").html( "Modification d'une catégorie" );
                  $("#id_input_category_name").val(category_supply_name);
                  $("#id_action").val("EDIT_CATEGORY_SUPPLY;"+Math.random());
              });
        });*/
        
        // BOOTSTRAP DATEPICKER WITH AUTO CLOSE
	// =================================================================
	// Require Bootstrap Datepicker
	// http://eternicode.github.io/bootstrap-datepicker/
	// =================================================================
	$('.input-group.date').datepicker({autoclose:true,format: 'dd/mm/yyyy',language:'fr'});
        
        // MORRIS AREA CHART
	// =================================================================
	// Require MorrisJS Chart
	// -----------------------------------------------------------------
	// http://morrisjs.github.io/morris.js/
	// =================================================================

        if($('#demo-morris-area').length > 0)
        {
            Morris.Area({
                    element: 'demo-morris-area',
                    data: [{
                            period: 'Janvier',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Février',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Mars',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Avril',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Mai',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Juin',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Juillet',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Aout',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Septembre',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Octobre',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Novembre',
                            dl: 0,
                            up: 0
                            }, {
                            period: 'Décembre',
                            dl: 0,
                            up: 0
                            }],
                    gridEnabled: false,
                    gridLineColor: 'transparent',
                    behaveLikeLine: true,
                    xkey: 'period',
                    ykeys: ['dl', 'up'],
                    labels: ['Achats', 'Chiffre d\'affaire'],
                    lineColors: ['#045d97'],
                    pointSize: 0,
                    pointStrokeColors : ['#045d97'],
                    lineWidth: 0,
                    resize:true,
                    hideHover: 'auto',
                    fillOpacity: 0.7,
                    parseTime:false
            });
        }
        
        

	// FORM WIZARD
	// =================================================================
	// Require Bootstrap Wizard
	// http://vadimg.com/twitter-bootstrap-wizard-example/
	// =================================================================


	// CIRCULAR FORM WIZARD
	// =================================================================
	$('#cir-wz').bootstrapWizard({
		tabClass		: 'wz-steps',
		nextSelector	: '.next',
		previousSelector	: '.previous',
		onTabClick: function(tab, navigation, index) {
		return false;
		},
		onInit : function(){
                    $('#cir-wz').find('.finish').hide().prop('disabled', true);
		},
		onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;
                    var $percent = (index/$total) * 100;
                    var margin = (100/$total)/2;
                    $('#cir-wz').find('.progress-bar').css({width:$percent+'%', 'margin': 0 + 'px ' + margin + '%'});

                    navigation.find('li:eq('+index+') a').trigger('focus');


                    // If it's the last tab then hide the last button and show the finish instead
                    if($current >= $total) {
                            $('#cir-wz').find('.next').hide();
                            $('#cir-wz').find('.finish').show();
                            $('#cir-wz').find('.finish').prop('disabled', false);
                    } else {
                            $('#cir-wz').find('.next').show();
                            $('#cir-wz').find('.finish').hide().prop('disabled', true);
                    }
		},
		onNext: function(){
                        isValid = null;
			$('#cir-wz-form').bootstrapValidator('validate');


			if(isValid === false)return false;
		}
                
	});
        
        

	// FORM VALIDATION
	// =================================================================
	// Require Bootstrap Validator
	// http://bootstrapvalidator.com/
	// =================================================================

	var isValid;
	$('#cir-wz-form').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
		valid: 'fa fa-check-circle fa-lg text-success',
		invalid: 'fa fa-times-circle fa-lg',
		validating: 'fa fa-refresh'
		},
		fields: {
                    input_email: {
                            validators: {
                                    notEmpty: {
                                            message: 'Email obligatoire'
                                    },
                                    emailAddress: {
                                            message: 'Email invalide'
                                    }
                            }
                    },
                    input_password: {
                            message: 'The username is not valid',
                            validators: {
                                    notEmpty: {
                                            message: 'Mot de passe obligatoire'
                                    }
                            }
                    },		
                    input_firstname: {
                            validators: {
                                    notEmpty: {
                                            message: 'Le prénom est obligatoire'
                                    },
                                    regexp: {
                                            regexp: /^[A-Z\s]+$/i,
                                            message: 'Le prénom est invalide'
                                    }
                            }
                    },
                    input_name: {
                            validators: {
                                    notEmpty: {
                                            message: 'Le nom est obligatoire'
                                    },
                                    regexp: {
                                            regexp: /^[A-Z\s]+$/i,
                                            message: 'Le nom est invalide'
                                    }
                            }
                    },		
                    address: {
                            validators: {
                                    notEmpty: {
                                            message: 'The address is required'
                                    }
                            }
                    },
                    input_companyname:{
                            validators: {
                                    notEmpty: {
                                            message: 'Le nom de la société est obligatoire'
                                    },
                                    regexp: {
                                            regexp: /^[A-Z\s]+$/i,
                                            message: 'Le nom est invalide'
                                    }                                    
                            }
                    }
		}
	}).on('success.field.bv', function(e, data) {
		// $(e.target)  --> The field element
		// data.bv      --> The BootstrapValidator instance
		// data.field   --> The field name
		// data.element --> The field element

		var $parent = data.element.parents('.form-group');

		// Remove the has-success class
		$parent.removeClass('has-success');


		// Hide the success icon
		//$parent.find('.form-control-feedback[data-bv-icon-for="' + data.field + '"]').hide();
	}).on('error.form.bv', function(e) {
		isValid = false;
	});


        // MODEL EDITION
	// =================================================================
	// 
	// =================================================================
        
        $.fn.editable.defaults.mode = 'inline';
        $('.td-editable').editable();
        $('.td-editable-price').editable({
            success: function(response, newValue) {
                var response = jQuery.parseJSON( response );
                $('#'+response.id+'_total').html( response.total+" &euro;" );
            }
        });
        
});
