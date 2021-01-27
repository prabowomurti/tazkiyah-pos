<?php 

$this->title = \common\models\Setting::t('app_name') . ' - Operator';

use kartik\select2\Select2;

?>
<div id="home" class="tab-pane">
    Home page is under construction
</div>

<!-- Customer -->
<div id="customer" class="tab-pane">
    Customer page is under construction
</div>
<!-- /Customer -->

<!-- ORDER -->
<div id="order" class="tab-pane active">

    <!-- .products -->

    <div class="col-xs-12" >
        <div class="col-xs-8">
            <div class="table-responsive div-cart">
                <table class="table table-hovered cart">
                    <thead>
                        <tr>
                            <th class="cart-th-description">DESCRIPTION</th>
                            <th class="cart-th-quantity">QTY</th>
                            <th class="cart-th-unit-price">UNIT PRICE</th>
                            <th class="cart-th-discount">DISCOUNT</th>
                            <th class="cart-th-subtotal">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="row-add_product" >
                            <td colspan="5" class="add_product">ADD PRODUCT</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
            <div class="col-lg-8 col-lg-offset-2 receipt_panel" style="display:none">
                <table class="table table-condensed receipt" id="receipt">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="3" style="font-size: 22px;padding: 12px 0px; background-color: #F9F9F9; color:#2A3F54;"><?= \common\models\Setting::t('app_name');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="subheader">
                            <td colspan="2">
                                #<span class="receipt_number"></span><br />
                                <span class="receipt_current_time" id="receipt_current_time"></span>
                            </td>
                            <td class="text-right">
                                Served by <span class="receipt_operator"><?= Yii::$app->user->identity['username']?></span><br />
                                <span class="receipt_customer"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><hr /></td>
                        </tr>
                        <tr>
                            <td colspan="2">Subtotal</td>
                            <td class="receipt_subtotal"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Discount</td>
                            <td class="receipt_discount"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Tax</td>
                            <td class="receipt_tax"></td>
                        </tr>
                        <tr>
                            <td colspan="3"><hr /></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>T O T A L</strong></td>
                            <td><strong class="receipt_total"></strong></td>
                        </tr>
                        <tr>
                            <td colspan="3"><hr /></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:center;"><?= \common\models\Setting::t('app_name');?><br /><?= \common\models\Setting::t('address');?><br /><?= \common\models\Setting::t('phone');?></td>
                        </tr>
                        <tr style="background-color: #DDD">
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="col-xs-4 summary">
            <div class="col-xs-12 customer">
                <div class="col-xs-11 customer_detail" data-customer-id="">Select Customer</div>
                <div class="col-xs-1 master_menu_button">
                    <span class="fa-stack" title="Master Menu">
                        <i class="fa fa-circle fa-stack-2x fa-inverse"></i>
                        <i class="fa fa-align-justify fa-stack-1x"></i>
                    </span>
                </div>
            </div>
            <div class="col-xs-12 subtotal">
                Subtotal <span class="pull-right" id="subtotal" data-value="0">0</span>
            </div>
            <div class="col-xs-12 tendered_panel_1" style="display:none">
                <div class="col-xs-3 clear_tendered">Clear</div>
                <div class="col-xs-3 tender_choice" data-value="1000">1,000</div>
                <div class="col-xs-3 tender_choice" data-value="2000">2,000</div>
                <div class="col-xs-3 tender_choice" data-value="5000">5,000</div>
            </div>
            <div class="col-xs-12 tendered_panel_2" style="display:none">
                <div class="col-xs-4 tender_choice" data-value="10000">10,000</div>
                <div class="col-xs-4 tender_choice" data-value="50000">50,000</div>
                <div class="col-xs-4 tender_choice" data-value="100000">100,000</div>
            </div>
            <div class="col-xs-12 discount">
                Discount <span class="pull-right">- <span id="discount" data-value="0" data-type="by_value">0</span></span>
            </div>
            <div class="col-xs-12 tax">
                Tax <span class="pull-right" id="tax" data-value="0">0</span>
            </div>
            <div class="col-xs-12 tendered_panel_3" style="display:none">
                Tendered <span class="pull-right tendered" data-value="0">0</span>
            </div>
            <div class="col-xs-12 total" id="total" data-value="0">
                0
            </div>
            <div class="col-xs-12 tendered_panel_4" style="display:none">
                Change <span class="pull-right change">-----</span>
            </div>
            <div class="col-xs-12 more-buttons dropup">
                <div class="col-xs-6 btn save-button">Save</div>
                <div class="col-xs-6 btn more-button dropdown-toggle" data-toggle="dropdown">More</div>
                <ul class="dropdown-menu dropdown-menu-right">
                    <!-- <li><a href="">Split Tender</a></li> -->
                    <li><a href="#" class="empty_cart_button">Empty Cart</a></li>
                    <li><a href="#" class="edit_order_note_button">Edit Note</a></li>
                </ul>
            </div>
            <div class="col-xs-12 cash-buttons">
                <div class="col-xs-12 btn cash" data-url="/operator/add-order">Cash</div>
                <!-- <div class="col-xs-12 btn cash">Credit</div> -->
            </div>
            <div class="col-xs-12 done_button" style="display:none">
                <button class="col-xs-12 btn print" disabled="disabled">Print Receipt</button>
                <button class="col-xs-12 btn done" disabled="disabled">Done</button>
            </div>
        </div>

    </div>
    <!-- /.col-xs-12 -->


    <div class="col-xs-12 products">
    <?php foreach (array_chunk($products, 6, true) as $products_chunk) :?>

        <div class="col-xs-12">
        <?php foreach($products_chunk as $product) : ?>
            <div class="col-xs-2 product" data-id="<?= $product->id?>">
                <?= $product->label;?>
            </div>
        <?php endforeach;?>
        </div>
    <?php endforeach;?>

    <table class="all_products hide">
        <?php foreach ($all_products as $index => $product) :
            $product_attribute_id = null;
            $product_price = $product->price;
            $product_label = $product->label;
            $product_attribute_groups = '';
            $product_attributes = '';

            $attribute_price = 0;
            if ($product->productAttributes) :

                $product_attribute_groups = json_encode($product->getAttributeGroups());

                $first_attribute = $product->productAttributes[0];
                $product_attribute_id = $first_attribute->id;
                $attribute_price = $first_attribute->price;
                $product_price += $attribute_price;
                $product_label .= ' <small>' . $first_attribute->attributeCombinationLabel . '</small>';
                $tmp = [];
                foreach ($product->productAttributes as $attribute)
                {
                    $tmp[] = [
                        'id' => $attribute->id,
                        'combination_ids' => $attribute->attributeCombinationIDs,
                        'label' => $attribute->attributeCombinationLabel,
                        'price' => $attribute->price];
                }
                $product_attributes = json_encode($tmp);
            endif;
        ?>
        <tr class="cart-item" data-product-id="<?= $product->id?>"
            data-product-attribute-id="<?= $product_attribute_id;?>"
            data-product-attribute-groups='<?= $product_attribute_groups;?>'
            data-product-parent-child-combination-ids='[{}]'
            data-product-attributes='<?= $product_attributes;?>'
            data-product-price="<?= $product_price;?>"
            data-product-attribute-price="<?= $attribute_price;?>"
            data-note=""
            data-quantity="1"
            data-discount="">
            <td class="cell-description"><?= $product_label;?></td>
            <td class="cell-quantity">
                <div class="input-group input-group-sm cell-quantity-input">
                    <span class="btn input-group-addon decrease-quantity"><span class="fa fa-minus"></span></span>
                    <input type="number" class="product-quantity form-control" min="1" value="1" step=0.01/>
                    <span class="btn input-group-addon increase-quantity"><span class="fa fa-plus"></span></span>
                </div>
            </td>
            <td class="cell-unit-price"><?= $product_price;?></td>
            <td class="cell-discount"><input type="number" class="cell-discount-input form-control input-sm" value="0" min="1" step=0.01/></td>
            <td class="cell-subtotal" data-value="<?= $product_price;?>"><?= $product_price;?></td>
        </tr>
        <?php endforeach;?>
    </table>
    </div>
    <!-- /.products -->
    
</div>
<!-- /ORDER -->

<div class="modal" id="master_menu_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Master Menu</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                        <span class="btn btn-sm btn-block btn-default master_menu">Order History</span>
                    </div>
                    <div class="col-sm-3">
                        <span class="btn btn-sm btn-block btn-default master_menu add_customer">Add New Customer</span>
                    </div>
                    <div class="col-sm-3">
                        <span class="btn btn-sm btn-block btn-default master_menu">More... </span>
                    </div>
                    <div class="col-sm-3">
                        <span class="btn btn-sm btn-block btn-default master_menu">Feature... </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <span class="btn btn-sm btn-block btn-default master_menu">is...</span>
                    </div>
                    <div class="col-sm-3">
                        <span class="btn btn-sm btn-block btn-default master_menu">Coming...</span>
                    </div>
                    <div class="col-sm-3">
                        <span class="btn btn-sm btn-block btn-default master_menu">Soon...</span>
                    </div>
                    <div class="col-sm-3">
                        <span class="btn btn-sm btn-block btn-default master_menu">(well, not so soon)</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="/site/logout" data-method="post" class="btn btn-danger">LOG OUT</a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL of .CART-ITEM EDIT -->
<div class="modal" id="edit_item_options_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Edit Item</h4>
            </div>
            <form class="form form-horizontal" id="form_edit_item_options">
                <input type="hidden" class="cart_item_index" value=""/>
                <div class="modal-body">
                    <div class="form-group edit_item_model_attribute hide">
                        <label class="control-label col-xs-4 col-xs-4" for="edit_item_modal_attributes"></label>
                        <div class="col-xs-6 ">
                            <select class="form-control edit_item_modal_attributes" data-parent-id=""></select>
                            <span id="edit_item_modal_attributes" data-value=""></span>
                        </div>
                    </div>

                    <div class="form-group price_attribute_delimiter">
                        <label class="control-label col-xs-4" for="item_note">Additional Price</label>
                        <div class="col-xs-6 ">
                            <input class="form-control" id="price_attribute_change" data-value='0' value="0" disabled="disabled" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-4" for="item_note">Item Note</label>
                        <div class="col-xs-6">
                            <input id="edit_item_modal_note" type="text" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="btn btn-lg btn-danger pull-left remove_from_cart_btn" >Remove</span>
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-lg btn-primary" value="Save"/>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL of .DISCOUNT EDIT -->
<div class="modal" id="edit_discount_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Edit Discount</h4>
            </div>
            <form class="form form-horizontal" id="form_edit_discount">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-4" for="edit_discount_type">Type</label>
                        <div class="col-xs-6">
                            <div class="btn-group discount_type_btn_group">
                                <button type="button" class="btn btn-default active discount_by_value">By Value</button>
                                <button type="button" class="btn btn-default discount_by_percentage">By Percentage</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-4" for="edit_discount_input">Discount</label>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <input id="edit_discount_input" type="number" step=0.01 min=0 class="form-control" value="0"/>
                                <span class="input-group-addon discount_percentage_symbol hide">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="pull-left btn btn-lg btn-default no_discount_btn">Clear Discount</span>
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-lg btn-primary" value="Save"/>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL of .TAX EDIT -->
<div class="modal" id="edit_tax_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Edit Tax</h4>
            </div>
            <form class="form form-horizontal" id="form_edit_tax">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-4" for="edit_tax_type">Type</label>
                        <div class="col-xs-6">
                            <div class="btn-group tax_type_btn_group">
                                <button type="button" class="btn btn-default active tax_by_value">By Value</button>
                                <button type="button" class="btn btn-default tax_by_percentage">By Percentage</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-4" for="edit_tax_input">Tax</label>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <input id="edit_tax_input" type="number" step=0.01 min=0 class="form-control" value="0"/>
                                <span class="input-group-addon tax_percentage_symbol hide">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="pull-left btn btn-lg btn-default no_tax_btn">Clear Tax</span>
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-lg btn-primary" value="Save"/>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL of Product Addition -->
<div class="modal" id="add_product_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Add Product</h4>
            </div>

            <form class="form form-horizontal" id="form_add_product">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12 col-xs-12">
                            <?= Select2::widget([
                                'name' => 'product_name',
                                'data' => $products_as_array,
                                'options' => ['placeholder' => 'Search Product', 'class' => 'btn-lg', 'id' => 'add_product_select2'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ]
                            ]);?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-default pull-left" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-lg btn-primary" value="Add to Cart" />
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Customer MODAL -->
<div class="modal" id="edit_customer_detail_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Select Customer</h4>
            </div>

            <form class="form form-horizontal" id="form_select_customer">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <?= Select2::widget([
                                'name' => 'customer_id',
                                'data' => $customers_as_array,
                                'options' => ['placeholder' => 'Select Customer', 'id' => 'customer_id'],
                                'pluginOptions' => ['allowClear' => true]
                            ]);?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="pull-left btn btn-lg btn-default clear_customer">Clear</span>
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-lg btn-primary" value="Save"/>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Customer MODAL -->
<div class="modal" id="add_customer_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Add New Customer</h4>
            </div>

            <form class="form form-horizontal" id="form_add_customer" data-url="/operator/add-customer">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-4" for="customer_name">Name</label>
                        <div class="col-xs-6">
                            <input id="customer_name" name="Customer[username]" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-4" for="customer_phone_number">Phone Number</label>
                        <div class="col-xs-6">
                            <input id="customer_phone_number" name="Customer[phone_number]" type="text" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-4" for="customer_gender">Gender</label>
                        <div class="col-xs-6">
                            <select name="Customer[gender]" id="customer_gender" class="form-control">
                                <option value="">Please Select Gender</option>
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-lg btn-primary" value="Save"/>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Order Note MODAL -->
<div class="modal" id="edit_order_note_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Edit Order Note</h4>
            </div>

            <form class="form form-horizontal" id="form_edit_order_note">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <textarea id="order_note" class="form-control" placeholder="Edit Order Note" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-lg btn-primary" value="Save"/>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Tendered Amount Modal -->
<div class="modal" id="edit_tendered_amount_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Tendered Amount</h4>
            </div>

            <form class="form form-horizontal" id="form_edit_tendered_amount">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="edit_tendered_amount_input" type="number" step=0.01 min=0 class="form-control" value="0"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save"/>
                </div>
            </form>
        </div>
    </div>
</div>
