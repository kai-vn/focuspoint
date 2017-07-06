<?php
class Meigee_Compo_Block_Adminhtml_PreviewProduct extends Varien_Data_Form_Element_Abstract
{


    public function getElementHtml()
    {
        return <<<HTML
		<div class="tabs-preview-wrapper">
		<div class="subtitle">Here you can check how the layout of a product page will look like.
		By default we use "One column" with custom sidebar in it but you also can switch to 2 columns with left or right sidebar or even 3 columns layout. To get it done go to "Catalog -> Manage Products -> Product Name -> Design Tab" and change "Page Layout" option.</div>

		<div class="preview-container one-column">
			<div class="preview-title">
				<h2>One column</h2>
			</div>
			<div class="option-preview custom-sidebar left" data-option="product_sidebar_position">
				<div class="option-value-preview" data-value="left">
					<div class="inner">
					<div class="sidebar-title">Custom Sidebar</div>
						<div class="option-preview block" data-option="product_compare">
							<div class="option-value-preview" data-value="v4">
								<div class="block-name">Compare</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_related">
							<div class="option-value-preview" data-value="v4">
								<div class="option-preview block" data-option="related_products">
									<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
										<div class="block-name">Related Slider</div>
									</div>
								</div>
								<div class="option-preview block" data-option="related_products">
									<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
										<div class="block-name">Related List</div>
									</div>
								</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_tags">
							<div class="option-value-preview" data-value="v4">
								<div class="block-name">Product tags</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_viewed">
							<div class="option-value-preview" data-value="v4">
								<div class="block-name">Viewed</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_wishlist">
							<div class="option-value-preview" data-value="v4">
								<div class="option-preview block" data-option="product_wishlist_type">
									<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
										<div class="block-name">Wishlist Slider</div>
									</div>
								</div>
								<div class="option-preview block" data-option="product_wishlist_type">
									<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
										<div class="block-name">Wishlist List</div>
									</div>
								</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_facebook">
							<div class="option-value-preview" data-value="v4">
								<div class="block-name">Facebook</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="option-preview custom-sidebar right" data-option="product_sidebar_position">
				<div class="option-value-preview" data-value="right">
					<div class="inner">
					<div class="sidebar-title">Custom Sidebar</div>
						<div class="option-preview block" data-option="product_compare">
							<div class="option-value-preview" data-value="v4">
								<div class="block-name">Compare</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_related">
							<div class="option-value-preview" data-value="v4">
								<div class="option-preview block" data-option="related_products">
									<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
										<div class="block-name">Related Slider</div>
									</div>
								</div>
								<div class="option-preview block" data-option="related_products">
									<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
										<div class="block-name">Related List</div>
									</div>
								</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_tags">
							<div class="option-value-preview" data-value="v4">
								<div class="block-name">Product tags</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_viewed">
							<div class="option-value-preview" data-value="v4">
								<div class="block-name">Viewed</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_wishlist">
							<div class="option-value-preview" data-value="v4">
								<div class="option-preview block" data-option="product_wishlist_type">
									<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
										<div class="block-name">Wishlist Slider</div>
									</div>
								</div>
								<div class="option-preview block" data-option="product_wishlist_type">
									<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
										<div class="block-name">Wishlist List</div>
									</div>
								</div>
							</div>
						</div>
						<div class="option-preview block" data-option="product_facebook">
							<div class="option-value-preview" data-value="v4">
								<div class="block-name">Facebook</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="product-container">
				<div class="inner">
					<div class="image-wrapper">
						<div class="image"><i class="fa fa-picture-o"></i></div>
						<div class="more-views">
							<div class="option-preview block" data-option="more_views">
								<div class="option-value-preview" data-value="catalog/product/view/more_views_slider.phtml">
									<div class="more-views-items">
										<div class="arrow">&lt;</div>
										<div class="slide"><i class="fa fa-picture-o"></i></div>
										<div class="slide"><i class="fa fa-picture-o"></i></div>
										<div class="arrow">&gt;</div>
									</div>
								</div>
							</div>
							<div class="option-preview block" data-option="more_views">
								<div class="option-value-preview" data-value="catalog/product/view/more_views.phtml">
									<div class="more-views-items list">
										<div class="slide"><i class="fa fa-picture-o"></i></div>
										<div class="slide"><i class="fa fa-picture-o"></i></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="content">
						<div class="title">Title</div>
						<div class="option-preview product-buttons" data-option="prev_next_buttons">
							<div class="option-value-preview" data-value="catalog/product/view/product_buttons_default.phtml">
								<div class="default-buttons">
									<div class="prev">&lt;</div>
									<div class="next">&gt;</div>
								</div>
							</div>
						</div>
						<div class="option-preview product-buttons" data-option="prev_next_buttons">
							<div class="option-value-preview" data-value="catalog/product/view/product_buttons.phtml">
								<div class="default-buttons">
									<div class="prev">&lt; Product</div>
									<div class="next">Product &gt;</div>
								</div>
							</div>
						</div>
						<div class="option-preview sku" data-option="product_sku">
							<div class="option-value-preview" data-value="1">
								<div class="block-name">Sku</div>
							</div>
						</div>
						<div class="desc">Morbi cursus hendrerit nulla. Nam laoreet ipsum id arcu dictum, vitae rutrum sapien maximus.</div>
						<div class="product-content option-preview" data-option="product_collateral_pos">
							<div class="option-value-preview" data-value="product-details">
								<div class="tabs option-preview" data-option="product_collateral">
									<div class="option-value-preview" data-value="catalog/product/view/collateral/tabs.phtml">
										<ul>
											<li class="active">Tab 1</li>
											<li>Tab 2</li>
											<li>Tab 3</li>
										</ul>
										<div class="tab-content">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
										</div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="vertical tabs option-preview" data-option="product_collateral">
									<div class="option-value-preview" data-value="catalog/product/view/collateral/vertical_tabs.phtml">
										<ul>
											<li class="active">Tab 1</li>
											<li>Tab 2</li>
											<li>Tab 3</li>
										</ul>
										<div class="tab-content">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
										</div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="collateral tabs option-preview" data-option="product_collateral">
									<div class="option-value-preview" data-value="catalog/product/view/collateral/simple_list.phtml">
										<div class="title">Tab 1</div>
										<div class="tab-content">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
										</div>
										<div class="title">Tab 2</div>
										<div class="tab-content">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
										</div>
										<div class="title">Tab 3</div>
										<div class="tab-content">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
										</div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="collateral tabs accordion option-preview" data-option="product_collateral">
									<div class="option-value-preview" data-value="catalog/product/view/collateral/accordion.phtml">
										<div class="title">Tab 1</div>
										<div class="tab-content">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
										</div>
										<div class="title">Tab 2</div>
										<div class="title">Tab 3</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
			<div class="under-product-content option-preview" data-option="product_collateral_pos">
				<div class="option-value-preview" data-value="bottom">
					<div class="tabs option-preview" data-option="product_collateral">
						<div class="option-value-preview" data-value="catalog/product/view/collateral/tabs.phtml">
							<ul>
								<li class="active">Tab 1</li>
								<li>Tab 2</li>
								<li>Tab 3</li>
							</ul>
							<div class="tab-content">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="vertical tabs option-preview" data-option="product_collateral">
						<div class="option-value-preview" data-value="catalog/product/view/collateral/vertical_tabs.phtml">
							<ul>
								<li class="active">Tab 1</li>
								<li>Tab 2</li>
								<li>Tab 3</li>
							</ul>
							<div class="tab-content">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="collateral tabs option-preview" data-option="product_collateral">
						<div class="option-value-preview" data-value="catalog/product/view/collateral/simple_list.phtml">
							<div class="title">Tab 1</div>
							<div class="tab-content">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
							</div>
							<div class="title">Tab 2</div>
							<div class="tab-content">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
							</div>
							<div class="title">Tab 3</div>
							<div class="tab-content">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="collateral tabs accordion option-preview" data-option="product_collateral">
						<div class="option-value-preview" data-value="catalog/product/view/collateral/accordion.phtml">
							<div class="title">Tab 1</div>
							<div class="tab-content">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
							</div>
							<div class="title">Tab 2</div>
							<div class="title">Tab 3</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="related-products-bottom option-preview" data-option="related_products_bottom">
				<div class="option-value-preview" data-value="catalog/product/list/related_bottom_slider.phtml">
					<div class="related-items">
						<div class="arrow">&lt;</div>
						<div class="slide"><i class="fa fa-picture-o"></i></div>
						<div class="slide"><i class="fa fa-picture-o"></i></div>
						<div class="slide"><i class="fa fa-picture-o"></i></div>
						<div class="slide"><i class="fa fa-picture-o"></i></div>
						<div class="arrow">&gt;</div>
					</div>
				</div>
			</div>
			<div class="related-products-bottom option-preview" data-option="related_products_bottom">
				<div class="option-value-preview" data-value="catalog/product/list/related_bottom.phtml">
					<div class="related-items list">
						<div class="slide"><i class="fa fa-picture-o"></i></div>
						<div class="slide"><i class="fa fa-picture-o"></i></div>
						<div class="slide"><i class="fa fa-picture-o"></i></div>
						<div class="slide"><i class="fa fa-picture-o"></i></div>
					</div>
				</div>
			</div>
		</div>


		<!--
		<div class="preview-container two-columns-left">
			<div class="preview-title">
				<h2>Two columns with left sidebar</h2>
			</div>
			<div class="left-sidebar">
				<div class="inner">
					<div class="option-preview block" data-option="product_compare">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Compare</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_related">
						<div class="option-value-preview" data-value="v2">
							<div class="option-preview block" data-option="related_products">
								<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
									<div class="block-name">Related Slider</div>
								</div>
							</div>
							<div class="option-preview block" data-option="related_products">
								<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
									<div class="block-name">Related List</div>
								</div>
							</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_poll">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Product poll</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_tags">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Product tags</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_viewed">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Viewed</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_subscribe">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Viewed</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_wishlist">
						<div class="option-value-preview" data-value="v2">
							<div class="option-preview block" data-option="product_wishlist_type">
								<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
									<div class="block-name">Wishlist Slider</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_wishlist_type">
								<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
									<div class="block-name">Wishlist List</div>
								</div>
							</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_facebook">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Facebook</div>
						</div>
					</div>
				</div>
			</div>
			<div class="preview-container-inner">
				<div class="option-preview custom-sidebar left" data-option="product_sidebar_position">
					<div class="option-value-preview" data-value="left">
						<div class="inner">
							<div class="option-preview block" data-option="product_compare">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Compare</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_related">
								<div class="option-value-preview" data-value="v4">
									<div class="option-preview block" data-option="related_products">
										<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
											<div class="block-name">Related Slider</div>
										</div>
									</div>
									<div class="option-preview block" data-option="related_products">
										<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
											<div class="block-name">Related List</div>
										</div>
									</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_tags">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Product tags</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_viewed">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Viewed</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_wishlist">
								<div class="option-value-preview" data-value="v4">
									<div class="option-preview block" data-option="product_wishlist_type">
										<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
											<div class="block-name">Wishlist Slider</div>
										</div>
									</div>
									<div class="option-preview block" data-option="product_wishlist_type">
										<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
											<div class="block-name">Wishlist List</div>
										</div>
									</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_facebook">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Facebook</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="option-preview custom-sidebar right" data-option="product_sidebar_position">
					<div class="option-value-preview" data-value="right">
						<div class="inner">
							<div class="option-preview block" data-option="product_compare">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Compare</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_related">
								<div class="option-value-preview" data-value="v4">
									<div class="option-preview block" data-option="related_products">
										<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
											<div class="block-name">Related Slider</div>
										</div>
									</div>
									<div class="option-preview block" data-option="related_products">
										<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
											<div class="block-name">Related List</div>
										</div>
									</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_tags">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Product tags</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_viewed">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Viewed</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_wishlist">
								<div class="option-value-preview" data-value="v4">
									<div class="option-preview block" data-option="product_wishlist_type">
										<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
											<div class="block-name">Wishlist Slider</div>
										</div>
									</div>
									<div class="option-preview block" data-option="product_wishlist_type">
										<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
											<div class="block-name">Wishlist List</div>
										</div>
									</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_facebook">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Facebook</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="product-container">
					<div class="inner">
						<div class="image-wrapper">
							<div class="image"></div>
							<div class="more-views">
								<div class="option-preview block" data-option="more_views">
									<div class="option-value-preview" data-value="catalog/product/view/more_views_slider.phtml">
										<div class="more-views-items">
											<div class="arrow">&lt;</div>
											<div class="slide"></div>
											<div class="slide"></div>
											<div class="arrow">&gt;</div>
										</div>
									</div>
								</div>
								<div class="option-preview block" data-option="more_views">
									<div class="option-value-preview" data-value="catalog/product/view/more_views.phtml">
										<div class="more-views-items list">
											<div class="slide"></div>
											<div class="slide"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="content">
							<div class="title">Title</div>
							<div class="option-preview product-buttons" data-option="prev_next_buttons">
								<div class="option-value-preview" data-value="catalog/product/view/product_buttons_default.phtml">
									<div class="default-buttons">
										<div class="prev">&lt;</div>
										<div class="next">&gt;</div>
									</div>
								</div>
							</div>
							<div class="option-preview product-buttons" data-option="prev_next_buttons">
								<div class="option-value-preview" data-value="catalog/product/view/product_buttons.phtml">
									<div class="default-buttons">
										<div class="prev">&lt; Product</div>
										<div class="next">Product &gt;</div>
									</div>
								</div>
							</div>
							<div class="option-preview sku" data-option="product_sku">
								<div class="option-value-preview" data-value="1">
									<div class="block-name">Sku</div>
								</div>
							</div>
							<div class="desc">Morbi cursus hendrerit nulla. Nam laoreet ipsum id arcu dictum, vitae rutrum sapien maximus.</div>
							<div class="product-content option-preview" data-option="product_collateral_pos">
								<div class="option-value-preview" data-value="product-details">
									<div class="tabs option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/tabs.phtml">
											<ul>
												<li class="active">Tab 1</li>
												<li>Tab 2</li>
												<li>Tab 3</li>
											</ul>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="vertical tabs option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/vertical_tabs.phtml">
											<ul>
												<li class="active">Tab 1</li>
												<li>Tab 2</li>
												<li>Tab 3</li>
											</ul>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="collateral tabs option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/simple_list.phtml">
											<div class="title">Tab 1</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="title">Tab 2</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="title">Tab 3</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="collateral tabs accordion option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/accordion.phtml">
											<div class="title">Tab 1</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="title">Tab 2</div>
											<div class="title">Tab 3</div>
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="under-product-content option-preview" data-option="product_collateral_pos">
					<div class="option-value-preview" data-value="bottom">
						<div class="tabs option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/tabs.phtml">
								<ul>
									<li class="active">Tab 1</li>
									<li>Tab 2</li>
									<li>Tab 3</li>
								</ul>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="vertical tabs option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/vertical_tabs.phtml">
								<ul>
									<li class="active">Tab 1</li>
									<li>Tab 2</li>
									<li>Tab 3</li>
								</ul>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="collateral tabs option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/simple_list.phtml">
								<div class="title">Tab 1</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="title">Tab 2</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="title">Tab 3</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="collateral tabs accordion option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/accordion.phtml">
								<div class="title">Tab 1</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="title">Tab 2</div>
								<div class="title">Tab 3</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="related-products-bottom option-preview" data-option="related_products_bottom">
					<div class="option-value-preview" data-value="catalog/product/list/related_bottom_slider.phtml">
						<div class="related-items">
							<div class="arrow">&lt;</div>
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="arrow">&gt;</div>
						</div>
					</div>
				</div>
				<div class="related-products-bottom option-preview" data-option="related_products_bottom">
					<div class="option-value-preview" data-value="catalog/product/list/related_bottom.phtml">
						<div class="related-items list">
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="slide"></div>
						</div>
					</div>
				</div>
			<div class="clear"></div>
			</div>
		</div>


		<div class="preview-container two-columns-right">
			<div class="preview-title">
				<h2>Two columns with right sidebar</h2>
			</div>
			<div class="right-sidebar">
				<div class="inner">
					<div class="option-preview block" data-option="product_compare">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Compare</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_related">
						<div class="option-value-preview" data-value="v3">
							<div class="option-preview block" data-option="related_products">
								<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
									<div class="block-name">Related Slider</div>
								</div>
							</div>
							<div class="option-preview block" data-option="related_products">
								<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
									<div class="block-name">Related List</div>
								</div>
							</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_poll">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Product poll</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_tags">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Product tags</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_viewed">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Viewed</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_subscribe">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Viewed</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_wishlist">
						<div class="option-value-preview" data-value="v3">
							<div class="option-preview block" data-option="product_wishlist_type">
								<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
									<div class="block-name">Wishlist Slider</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_wishlist_type">
								<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
									<div class="block-name">Wishlist List</div>
								</div>
							</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_facebook">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Facebook</div>
						</div>
					</div>
				</div>
			</div>
			<div class="preview-container-inner">
				<div class="option-preview custom-sidebar left" data-option="product_sidebar_position">
					<div class="option-value-preview" data-value="left">
						<div class="inner">
							<div class="option-preview block" data-option="product_compare">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Compare</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_related">
								<div class="option-value-preview" data-value="v4">
									<div class="option-preview block" data-option="related_products">
										<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
											<div class="block-name">Related Slider</div>
										</div>
									</div>
									<div class="option-preview block" data-option="related_products">
										<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
											<div class="block-name">Related List</div>
										</div>
									</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_tags">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Product tags</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_viewed">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Viewed</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_wishlist">
								<div class="option-value-preview" data-value="v4">
									<div class="option-preview block" data-option="product_wishlist_type">
										<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
											<div class="block-name">Wishlist Slider</div>
										</div>
									</div>
									<div class="option-preview block" data-option="product_wishlist_type">
										<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
											<div class="block-name">Wishlist List</div>
										</div>
									</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_facebook">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Facebook</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="option-preview custom-sidebar right" data-option="product_sidebar_position">
					<div class="option-value-preview" data-value="right">
						<div class="inner">
							<div class="option-preview block" data-option="product_compare">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Compare</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_related">
								<div class="option-value-preview" data-value="v4">
									<div class="option-preview block" data-option="related_products">
										<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
											<div class="block-name">Related Slider</div>
										</div>
									</div>
									<div class="option-preview block" data-option="related_products">
										<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
											<div class="block-name">Related List</div>
										</div>
									</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_tags">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Product tags</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_viewed">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Viewed</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_wishlist">
								<div class="option-value-preview" data-value="v4">
									<div class="option-preview block" data-option="product_wishlist_type">
										<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
											<div class="block-name">Wishlist Slider</div>
										</div>
									</div>
									<div class="option-preview block" data-option="product_wishlist_type">
										<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
											<div class="block-name">Wishlist List</div>
										</div>
									</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_facebook">
								<div class="option-value-preview" data-value="v4">
									<div class="block-name">Facebook</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="product-container">
					<div class="inner">
						<div class="image-wrapper">
							<div class="image"></div>
							<div class="more-views">
								<div class="option-preview block" data-option="more_views">
									<div class="option-value-preview" data-value="catalog/product/view/more_views_slider.phtml">
										<div class="more-views-items">
											<div class="arrow">&lt;</div>
											<div class="slide"></div>
											<div class="slide"></div>
											<div class="arrow">&gt;</div>
										</div>
									</div>
								</div>
								<div class="option-preview block" data-option="more_views">
									<div class="option-value-preview" data-value="catalog/product/view/more_views.phtml">
										<div class="more-views-items list">
											<div class="slide"></div>
											<div class="slide"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="content">
							<div class="title">Title</div>
							<div class="option-preview product-buttons" data-option="prev_next_buttons">
								<div class="option-value-preview" data-value="catalog/product/view/product_buttons_default.phtml">
									<div class="default-buttons">
										<div class="prev">&lt;</div>
										<div class="next">&gt;</div>
									</div>
								</div>
							</div>
							<div class="option-preview product-buttons" data-option="prev_next_buttons">
								<div class="option-value-preview" data-value="catalog/product/view/product_buttons.phtml">
									<div class="default-buttons">
										<div class="prev">&lt; Product</div>
										<div class="next">Product &gt;</div>
									</div>
								</div>
							</div>
							<div class="option-preview sku" data-option="product_sku">
								<div class="option-value-preview" data-value="1">
									<div class="block-name">Sku</div>
								</div>
							</div>
							<div class="desc">Morbi cursus hendrerit nulla. Nam laoreet ipsum id arcu dictum, vitae rutrum sapien maximus.</div>
							<div class="product-content option-preview" data-option="product_collateral_pos">
								<div class="option-value-preview" data-value="product-details">
									<div class="tabs option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/tabs.phtml">
											<ul>
												<li class="active">Tab 1</li>
												<li>Tab 2</li>
												<li>Tab 3</li>
											</ul>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="vertical tabs option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/vertical_tabs.phtml">
											<ul>
												<li class="active">Tab 1</li>
												<li>Tab 2</li>
												<li>Tab 3</li>
											</ul>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="collateral tabs option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/simple_list.phtml">
											<div class="title">Tab 1</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="title">Tab 2</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="title">Tab 3</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="collateral tabs accordion option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/accordion.phtml">
											<div class="title">Tab 1</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="title">Tab 2</div>
											<div class="title">Tab 3</div>
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="under-product-content option-preview" data-option="product_collateral_pos">
					<div class="option-value-preview" data-value="bottom">
						<div class="tabs option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/tabs.phtml">
								<ul>
									<li class="active">Tab 1</li>
									<li>Tab 2</li>
									<li>Tab 3</li>
								</ul>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="vertical tabs option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/vertical_tabs.phtml">
								<ul>
									<li class="active">Tab 1</li>
									<li>Tab 2</li>
									<li>Tab 3</li>
								</ul>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="collateral tabs option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/simple_list.phtml">
								<div class="title">Tab 1</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="title">Tab 2</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="title">Tab 3</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="collateral tabs accordion option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/accordion.phtml">
								<div class="title">Tab 1</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="title">Tab 2</div>
								<div class="title">Tab 3</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="related-products-bottom option-preview" data-option="related_products_bottom">
					<div class="option-value-preview" data-value="catalog/product/list/related_bottom_slider.phtml">
						<div class="related-items">
							<div class="arrow">&lt;</div>
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="arrow">&gt;</div>
						</div>
					</div>
				</div>
				<div class="related-products-bottom option-preview" data-option="related_products_bottom">
					<div class="option-value-preview" data-value="catalog/product/list/related_bottom.phtml">
						<div class="related-items list">
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="slide"></div>
							<div class="slide"></div>
						</div>
					</div>
				</div>
			<div class="clear"></div>
			</div>
		</div> -->

		<div class="preview-container three-columns">
			<div class="preview-title">
				<h2>Three columns</h2>
			</div>
			<div class="left-sidebar">
				<div class="inner">
					<div class="sidebar-title">Left Sidebar</div>
					<div class="option-preview block" data-option="product_compare">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Compare</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_related">
						<div class="option-value-preview" data-value="v2">
							<div class="option-preview block" data-option="related_products">
								<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
									<div class="block-name">Related Slider</div>
								</div>
							</div>
							<div class="option-preview block" data-option="related_products">
								<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
									<div class="block-name">Related List</div>
								</div>
							</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_poll">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Product poll</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_tags">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Product tags</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_viewed">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Viewed</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_subscribe">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Viewed</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_wishlist">
						<div class="option-value-preview" data-value="v2">
							<div class="option-preview block" data-option="product_wishlist_type">
								<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
									<div class="block-name">Wishlist Slider</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_wishlist_type">
								<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
									<div class="block-name">Wishlist List</div>
								</div>
							</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_facebook">
						<div class="option-value-preview" data-value="v2">
							<div class="block-name">Facebook</div>
						</div>
					</div>
				</div>
			</div>
			<div class="right-sidebar">
				<div class="inner">
					<div class="sidebar-title">Left Sidebar</div>
					<div class="option-preview block" data-option="product_compare">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Compare</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_related">
						<div class="option-value-preview" data-value="v3">
							<div class="option-preview block" data-option="related_products">
								<div class="option-value-preview" data-value="catalog/product/list/related_slider.phtml">
									<div class="block-name">Related Slider</div>
								</div>
							</div>
							<div class="option-preview block" data-option="related_products">
								<div class="option-value-preview" data-value="catalog/product/list/related.phtml">
									<div class="block-name">Related List</div>
								</div>
							</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_poll">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Product poll</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_tags">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Product tags</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_viewed">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Viewed</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_subscribe">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Viewed</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_wishlist">
						<div class="option-value-preview" data-value="v3">
							<div class="option-preview block" data-option="product_wishlist_type">
								<div class="option-value-preview" data-value="wishlist/sidebar_slider.phtml">
									<div class="block-name">Wishlist Slider</div>
								</div>
							</div>
							<div class="option-preview block" data-option="product_wishlist_type">
								<div class="option-value-preview" data-value="wishlist/sidebar.phtml">
									<div class="block-name">Wishlist List</div>
								</div>
							</div>
						</div>
					</div>
					<div class="option-preview block" data-option="product_facebook">
						<div class="option-value-preview" data-value="v3">
							<div class="block-name">Facebook</div>
						</div>
					</div>
				</div>
			</div>
			<div class="preview-container-inner">
				<div class="product-container">
					<div class="inner">
						<div class="image-wrapper">
							<div class="image"><i class="fa fa-picture-o"></i></div>
							<div class="more-views">
								<div class="option-preview block" data-option="more_views">
									<div class="option-value-preview" data-value="catalog/product/view/more_views_slider.phtml">
										<div class="more-views-items">
											<div class="arrow">&lt;</div>
											<div class="slide"><i class="fa fa-picture-o"></i></div>
											<div class="slide"><i class="fa fa-picture-o"></i></div>
											<div class="arrow">&gt;</div>
										</div>
									</div>
								</div>
								<div class="option-preview block" data-option="more_views">
									<div class="option-value-preview" data-value="catalog/product/view/more_views.phtml">
										<div class="more-views-items list">
											<div class="slide"><i class="fa fa-picture-o"></i></div>
											<div class="slide"><i class="fa fa-picture-o"></i></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="content">
							<div class="title">Title</div>
							<div class="option-preview product-buttons" data-option="prev_next_buttons">
								<div class="option-value-preview" data-value="catalog/product/view/product_buttons_default.phtml">
									<div class="default-buttons">
										<div class="prev">&lt;</div>
										<div class="next">&gt;</div>
									</div>
								</div>
							</div>
							<div class="option-preview product-buttons" data-option="prev_next_buttons">
								<div class="option-value-preview" data-value="catalog/product/view/product_buttons.phtml">
									<div class="default-buttons">
										<div class="prev">&lt; Product</div>
										<div class="next">Product &gt;</div>
									</div>
								</div>
							</div>
							<div class="option-preview sku" data-option="product_sku">
								<div class="option-value-preview" data-value="1">
									<div class="block-name">Sku</div>
								</div>
							</div>
							<div class="desc">Morbi cursus hendrerit nulla. Nam laoreet ipsum id arcu dictum, vitae rutrum sapien maximus.</div>
							<div class="product-content option-preview" data-option="product_collateral_pos">
								<div class="option-value-preview" data-value="product-details">
									<div class="tabs option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/tabs.phtml">
											<ul>
												<li class="active">Tab 1</li>
												<li>Tab 2</li>
												<li>Tab 3</li>
											</ul>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="vertical tabs option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/vertical_tabs.phtml">
											<ul>
												<li class="active">Tab 1</li>
												<li>Tab 2</li>
												<li>Tab 3</li>
											</ul>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="collateral tabs option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/simple_list.phtml">
											<div class="title">Tab 1</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="title">Tab 2</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="title">Tab 3</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="collateral tabs accordion option-preview" data-option="product_collateral">
										<div class="option-value-preview" data-value="catalog/product/view/collateral/accordion.phtml">
											<div class="title">Tab 1</div>
											<div class="tab-content">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
											</div>
											<div class="title">Tab 2</div>
											<div class="title">Tab 3</div>
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="under-product-content option-preview" data-option="product_collateral_pos">
					<div class="option-value-preview" data-value="bottom">
						<div class="tabs option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/tabs.phtml">
								<ul>
									<li class="active">Tab 1</li>
									<li>Tab 2</li>
									<li>Tab 3</li>
								</ul>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="vertical tabs option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/vertical_tabs.phtml">
								<ul>
									<li class="active">Tab 1</li>
									<li>Tab 2</li>
									<li>Tab 3</li>
								</ul>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="collateral tabs option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/simple_list.phtml">
								<div class="title">Tab 1</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="title">Tab 2</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="title">Tab 3</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="collateral tabs accordion option-preview" data-option="product_collateral">
							<div class="option-value-preview" data-value="catalog/product/view/collateral/accordion.phtml">
								<div class="title">Tab 1</div>
								<div class="tab-content">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								</div>
								<div class="title">Tab 2</div>
								<div class="title">Tab 3</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="related-products-bottom option-preview" data-option="related_products_bottom">
					<div class="option-value-preview" data-value="catalog/product/list/related_bottom_slider.phtml">
						<div class="related-items">
							<div class="arrow">&lt;</div>
							<div class="slide"><i class="fa fa-picture-o"></i></div>
							<div class="slide"><i class="fa fa-picture-o"></i></div>
							<div class="slide"><i class="fa fa-picture-o"></i></div>
							<div class="slide"><i class="fa fa-picture-o"></i></div>
							<div class="arrow">&gt;</div>
						</div>
					</div>
				</div>
				<div class="related-products-bottom option-preview" data-option="related_products_bottom">
					<div class="option-value-preview" data-value="catalog/product/list/related_bottom.phtml">
						<div class="related-items list">
							<div class="slide"><i class="fa fa-picture-o"></i></div>
							<div class="slide"><i class="fa fa-picture-o"></i></div>
							<div class="slide"><i class="fa fa-picture-o"></i></div>
							<div class="slide"><i class="fa fa-picture-o"></i></div>
						</div>
					</div>
				</div>
			<div class="clear"></div>
			</div>
		</div>
	<div class="clear"></div>
</div>
HTML;

    }



}

