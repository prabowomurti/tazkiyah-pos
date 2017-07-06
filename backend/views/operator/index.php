<?php 

$this->title = \common\models\Setting::t('app_name') . ' - Operator';
?>
<div id="home" class="tab-pane">
    Home
</div>

<!-- Customer -->
<div id="customer" class="tab-pane">
    Customer
</div>
<!-- /Customer -->

<!-- ORDER -->
<div id="order" class="tab-pane active">
    <!-- <div class="col-lg-3 col-md-2 col-sm-2 col-xs-4">
        <span class="btn btn-primary btn-md">Add Order</span>
    </div>
    <div class="col-lg-9 col-md-10 col-sm-10 col-xs-8 pull-right">
        <div class="input-group input-group-md">
            <input type="text" class="form-control" placeholder="Customer Name, Order ID, or Code">
            <span class="input-group-addon">Search !</span>
        </div>
    </div>
    <div class="clearfix"></div> -->

    <!-- .products -->

    <div class="col-xs-12">
        <div class="col-xs-8">
            <div class="table-responsive">
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
                        <tr class="row-add_product">
                            <td colspan="5" class="add_product">Add Product</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->

        </div>
        <div class="col-xs-4 summary">
            <div class="customer">
                <u>Herry Drewback</u>
                <span class="pull-right">
                    <span class="fa-stack add-customer">
                    <i class="fa fa-circle fa-stack-2x fa-inverse"></i>
                    <i class="fa fa-plus fa-stack-1x"></i>
                    </span>
                </span>
            </div>
            <div class="col-xs-12 subtotal">
                Subtotal <span class="pull-right" id="subtotal" data-value="0">0</span>
            </div>
            <div class="col-xs-12 discount">
                Discount <span class="pull-right">- <span id="discount" data-value="0" data-type="by_value">0</span></span>
            </div>
            <div class="col-xs-12 tax">
                Tax <span class="pull-right" id="tax" data-value="0">0</span>
            </div>
            <div class="col-xs-12 total" id="total" data-value="0">
                0
            </div>
            <div class="col-xs-12 cash-buttons">
                <div class="col-xs-6 btn cash">Cash</div>
                <div class="col-xs-6 btn credit">Credit</div>
            </div>
        </div>

    </div>
    <!-- /.col-xs-12 -->


    <div class="col-xs-12 products">
    <?php foreach (array_chunk($products, 6, true) as $products_chunk) :?>

        <div class="col-xs-12">
        <?php foreach($products_chunk as $product) : ?>
            <div class="col-xs-2 product" data-id="<?= $product->id?>" data-label="<?= $product->label;?>" data-price="<?= $product->price?>">
                <?= $product->label;?>
                <?php if ( ! empty($product->productAttributes)) : ?>
                <span class="attributes hidden">
                    <?php foreach ($product->productAttributes as $product_attribute) : ?>
                    <span class="attribute" data-id="<?= $product_attribute->id?>" 
                        data-label="<?= $product_attribute->attributeCombinationLabel?>" 
                        data-price="<?= $product_attribute->price?>"></span>
                    <?php endforeach;?>
                </span>
                <?php endif;?>
            </div>
        <?php endforeach;?>
        </div>
    <?php endforeach;?>
    <table class="product_template_for_cart hidden">
        <tr class="cart-item" data-product-id="" data-product-attribute-id="" data-product-attributes='' data-product-price="" data-product-attribute-price="" data-note="" data-quantity="">
            <td class="cell-description"></td>
            <td class="cell-quantity">
                <div class="input-group input-group-sm cell-quantity-input">
                    <span class="btn input-group-addon decrease-quantity"><span class="fa fa-minus"></span></span>
                    <input type="number" class="product-quantity form-control" min="1" value="1" step=0.01/>
                    <span class="btn input-group-addon increase-quantity"><span class="fa fa-plus"></span></span>
                </div>
            </td>
            <td class="cell-unit-price"></td>
            <td class="cell-discount"><input type="number" class="cell-discount-input form-control input-sm" value="0" min="1" step=0.01/></td>
            <td class="cell-subtotal"></td>
        </tr>
    </table>
    </div>
    <!-- /.products -->

    <div class="col-xs-12 text-center search-products">
        <span class="btn btn-sm btn-default" style="width: 400px">&#10507;&#10507;&#10507;</span>
    </div>
    
    
</div>
<!-- /ORDER -->

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
                    <div class="form-group edit_item_model_attribute">
                        <label class="control-label col-md-4" for="edit_item_modal_attributes">Product Attribute</label>
                        <div class="col-md-6">
                            <select id="edit_item_modal_attributes" name="attributes" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="item_note">Item Note</label>
                        <div class="col-md-6">
                            <input id="edit_item_modal_note" type="text" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="btn btn-danger pull-left remove_from_cart_btn" >Remove</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save"/>
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
                        <label class="control-label col-md-4" for="edit_discount_type">Type</label>
                        <div class="col-md-6">
                            <div class="btn-group discount_type_btn_group">
                                <button type="button" class="btn btn-default active discount_by_value">By Value</button>
                                <button type="button" class="btn btn-default discount_by_percentage">By Percentage</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="edit_discount_input">Discount</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input id="edit_discount_input" type="number" step=0.01 min=0 class="form-control" value="0"/>
                                <span class="input-group-addon discount_percentage_symbol hide">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="pull-left btn btn-default no_discount_btn">Clear Discount</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save"/>
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
                        <label class="control-label col-md-4" for="edit_tax_type">Type</label>
                        <div class="col-md-6">
                            <div class="btn-group tax_type_btn_group">
                                <button type="button" class="btn btn-default active tax_by_value">By Value</button>
                                <button type="button" class="btn btn-default tax_by_percentage">By Percentage</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="edit_tax_input">Tax</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input id="edit_tax_input" type="number" step=0.01 min=0 class="form-control" value="0"/>
                                <span class="input-group-addon tax_percentage_symbol hide">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="pull-left btn btn-default no_tax_btn">Clear Tax</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save"/>
                </div>
            </form>
        </div>
    </div>
</div>