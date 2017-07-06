<?php
class Meigee_Compo_Block_Adminhtml_PreviewListing extends Varien_Data_Form_Element_Abstract
{


    public function getElementHtml()
    {
        return <<<HTML
		<div class="tabs-preview-wrapper">

		<div class="subtitle">Here you can check how the layout of category pages will look like. Just play with options below to customize that page.</div>

		<div class="listing-preview">
	<div class="content">
		<ul>
			<li class="item">
			<div class="preview-title">
				<h2>Preview of the product</h2>
			</div>
				<div class="image-box">
					<div class="product-labels-wrapper">
						<div class="option-preview top" data-option="product_label_pos">
							<div class="option-value-preview" data-value="top">
								<div class="option-preview new-sale" data-option="product_label_order">
									<div class="option-value-preview" data-value="new-sale">
										<div class="option-preview label-type-1" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-1">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-2" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-2">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-3" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-3">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-4" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-4">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="option-preview sale-new" data-option="product_label_order">
									<div class="option-value-preview" data-value="sale-new">
										<div class="option-preview label-type-1" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-1">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-2" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-2">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-3" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-3">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-4" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-4">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="option-preview bottom" data-option="product_label_pos">
							<div class="option-value-preview" data-value="bottom">
								<div class="option-preview new-sale" data-option="product_label_order">
									<div class="option-value-preview" data-value="new-sale">
										<div class="option-preview label-type-1" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-1">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-2" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-2">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-3" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-3">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-4" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-4">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="option-preview sale-new" data-option="product_label_order">
									<div class="option-value-preview" data-value="sale-new">
										<div class="option-preview label-type-1" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-1">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-2" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-2">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-3" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-3">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
										<div class="option-preview label-type-4" data-option="product_label_view">
											<div class="option-value-preview" data-value="label-type-4">
												<div class="option-preview label-new" data-option="product_label_new">
													<div class="option-value-preview" data-value="1">
														New
													</div>
												</div>
												<div class="option-preview label-sale" data-option="product_label_sale">
													<div class="option-value-preview" data-value="1">
														Sale
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="product-image">
						<i class="fa fa-picture-o"></i>
						<button type="button" title="" class="option-preview quickview btn btn-default" data-option="listing_quickview">
							<div class="option-value-preview" data-value="1">
								<span><span>Quick View</span></span>
							</div>
						</button>
					</div>
				</div>
				<div class="option-preview product-name" data-option="listing_name">
					<div class="option-value-preview" data-value="1">
						Geometric Candle Holders
					</div>
				</div>
				<div class="option-preview availability in-stock" data-option="listing_stock">
					<div class="option-value-preview" data-value="1">
						Product <span>In stock</span>
					</div>
				</div>
				<div class="option-preview desc std" data-option="listing_desc">
					<div class="option-value-preview" data-value="1">
						A simple and stylish way to add warmth and dimension to any room. Perfect for gifting.
					</div>
				</div>
				<div class="ratings">
					<div class="option-preview rating-box" data-option="listing_rating_stars">
						<div class="option-value-preview" data-value="1">
							<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
							<div class="rating" style="width:60%"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
						</div>
					</div>
					<div class="rating-links">
						<span class="option-preview" data-option="listing_rating_customer">
							<div class="option-value-preview" data-value="1">
								1 Review(s)
							</div>
						</span>
						<span class="option-preview" data-option="listing_rating_review">
							<div class="option-value-preview" data-value="1">
								<span class="separator">|</span> Add Your Review
							</div>
						</span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="option-preview price-box" data-option="listing_price">
					<div class="option-value-preview" data-value="1">
						<span class="regular-price" id="product-price-437">
							<span class="price">$123.85</span>
						</span>
					</div>
				</div>
				<div class="option-preview timer-box clearfix" data-option="timer_listing_status">
					<div class="option-value-preview" data-value="1">
						<div class="option-preview title" data-option="timer_listing_title">
							<div class="option-value-preview" data-value="1">
								Offer ends in
							</div>
						</div>
						<div class="option-preview" data-option="timer_listing_format">
							<div class="option-value-preview" data-value="1">
								<div class="option-preview days" data-option="timer_listing_display">
									<div class="option-value-preview" data-value="1">
										<span>07</span>d
									</div>
								</div>
								<div class="hours"><span>22</span>h</div>
								<div class="minutes"><span>16</span>m</div>
								<div class="seconds"><span>46</span>s</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="option-preview" data-option="timer_listing_format">
							<div class="option-value-preview" data-value="0">
								<div class="option-preview days" data-option="timer_listing_display">
									<div class="option-value-preview" data-value="1">
										<span>07</span>
									</div>
								</div>
								<div class="hours"><span>22</span></div>
								<div class="minutes"><span>16</span></div>
								<div class="seconds"><span>46</span></div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="actions">
					<button type="button" title="" class="option-preview btn btn-default" data-option="listing_cart_btn">
						<div class="option-value-preview" data-value="1">
							<span><span><i class="fa fa-shopping-cart"></i>Add to Cart</span></span>
						</div>
					</button>
					<ul class="add-to-links">
						<li class="option-preview" data-option="listing_wishlist_btn">
							<div class="option-value-preview" data-value="1">
								<span class="link-wishlist"><i class="fa fa-heart-o"></i></span>
							</div>
						</li>
						<li class="option-preview" data-option="listing_compare_btn">
							<div class="option-value-preview" data-value="1">
								<span class="link-compare"><i class="fa fa-bar-chart-o"></i></span>
							</div>
						</li>
					</ul>
				</div>
			</li>
			<li class="clear"></li>
		</ul>
	</div>
</div>

<div class="listing-second-preview">
	<div class="preview-title">
		<h2>Preview of the layout</h2>
	</div>
	<div class="option-preview sidebar-left" data-option="listing_sidebar_position">
		<div class="option-value-preview" data-value="sidebar-left">
			<div class="inner">
			Left Sidebar
			</div>
		</div>
	</div>
	<div class="content">
		<div class="option-preview two-products" data-option="listing_columns">
			<div class="option-value-preview" data-value="two-columns">
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
			</div>
		</div>
		<div class="option-preview three-products" data-option="listing_columns">
			<div class="option-value-preview" data-value="three-columns">
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
			</div>
		</div>
		<div class="option-preview four-products" data-option="listing_columns">
			<div class="option-value-preview" data-value="four-columns">
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
			</div>
		</div>
		<div class="option-preview five-products" data-option="listing_columns">
			<div class="option-value-preview" data-value="five-columns">
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
			</div>
		</div>
		<div class="option-preview six-products" data-option="listing_columns">
			<div class="option-value-preview" data-value="six-columns">
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
			</div>
		</div>
		<div class="option-preview seven-products" data-option="listing_columns">
			<div class="option-value-preview" data-value="seven-columns">
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
			</div>
		</div>
		<div class="option-preview eight-products" data-option="listing_columns">
			<div class="option-value-preview" data-value="eight-columns">
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
				<div class="product-image"><i class="fa fa-picture-o"></i></div>
			</div>
		</div>
	</div>
	<div class="option-preview sidebar-right" data-option="listing_sidebar_position">
		<div class="option-value-preview" data-value="sidebar-right">
			<div class="inner">
			Right Sidebar
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
HTML;

    }



}

