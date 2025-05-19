(function(blocks, i18n, element, components, blockEditor) {
	var el = element.createElement;
	var __ = i18n.__;

	blocks.registerBlockType('mrvp/recently-viewed', {
		title: __('MATE Recently Viewed Products', 'mate-recently-viewed-products'),
		icon: 'visibility',
		category: 'widgets',
		attributes: {
			title: { type: 'string', default: '' },
			count: { type: 'number', default: 5 },
			showImage: { type: 'boolean', default: true },
			showPrice: { type: 'boolean', default: false },
			showExcerpt: { type: 'boolean', default: false }
			
		},
		edit: function(props) {
			var atts = props.attributes;
			function update(attr, value) {
				var newAtts = {};
				newAtts[attr] = value;
				props.setAttributes(newAtts);
			}

			// Static mock preview HTML
			var preview = `
			<div class="mrvp-wrapper" style="border: 1px solid #ddd; padding: 20px;">
			<div class="components-placeholder__label"><span class="dashicon dashicons dashicons-visibility"></span> MATE Recently Viewed Products</div>
			<div class="components-placeholder__instructions">The preview is only visible on the frontend</div>
			<ul class="mrvp-product-list" style="padding: 0; margin: 10px 0 0 0;">
				<li class="mrvp-product-link" style="list-style: none; display: flex; align-items: center; padding: 10px 0; border-top: none;">
					<div style="width: 40px; height: 40px; background: #ccc; border-radius: 4px; margin-right: 12px; flex-shrink: 0;"></div>
					<div style="background: #eee; height: 16px; width: 80%;"></div>
				</li>
				<li class="mrvp-product-link" style="list-style: none; display: flex; align-items: center; padding: 10px 0; border-top: 1px solid #ccc;">
					<div style="width: 40px; height: 40px; background: #ccc; border-radius: 4px; margin-right: 12px; flex-shrink: 0;"></div>
					<div style="background: #eee; height: 16px; width: 60%;"></div>
				</li>
			</ul>
			</div>
		`;

			return el('div', { className: props.className },
				el(blockEditor.InspectorControls, {},
					el(components.PanelBody, { title: __('Block Settings', 'mate') },
						el(components.TextControl, {
							label: __('Title', 'mate'),
							value: atts.title,
							onChange: function(val) { update('title', val); }
						}),
						el(components.TextControl, {
							label: __('Number of Products', 'mate'),
							type: 'number',
							value: atts.count,
							onChange: function(val) { update('count', parseInt(val) || 1); }
						}),
						el(components.ToggleControl, {
							label: __('Show Product Image', 'mate'),
							checked: props.attributes.showImage,
							onChange: (val) => props.setAttributes({ showImage: val })
						}),
						el(components.ToggleControl, {
							label: __('Show Price', 'mate'),
							checked: props.attributes.showPrice,
							onChange: (val) => props.setAttributes({ showPrice: val })
						}),
						
						el(components.ToggleControl, {
							label: __('Show Description', 'mate'),
							checked: props.attributes.showExcerpt,
							onChange: (val) => props.setAttributes({ showExcerpt: val })
						}),		
					)
				),
				el('div', { dangerouslySetInnerHTML: { __html: preview } })
			);
		},
		save: function() {
			return null; // dynamic block
		}
	});
})(window.wp.blocks, window.wp.i18n, window.wp.element, window.wp.components, window.wp.blockEditor);
