/**
 * Weavit Page Designer — editor UI for the Section / Hero / Button
 * blocks (inc/components/*.php does the server-side registration +
 * rendering). Hand-written against the Block Editor API directly, no
 * build step, same approach as editor-panel.js.
 */
( function ( wp ) {
	var el                 = wp.element.createElement;
	var Fragment            = wp.element.Fragment;
	var registerBlockType  = wp.blocks.registerBlockType;
	var useBlockProps      = wp.blockEditor.useBlockProps;
	var InspectorControls  = wp.blockEditor.InspectorControls;
	var InnerBlocks        = wp.blockEditor.InnerBlocks;
	var RichText            = wp.blockEditor.RichText;
	var MediaUpload         = wp.blockEditor.MediaUpload;
	var MediaUploadCheck    = wp.blockEditor.MediaUploadCheck;
	var ColorPalette        = wp.blockEditor.ColorPalette;
	var PanelBody            = wp.components.PanelBody;
	var TextControl          = wp.components.TextControl;
	var SelectControl        = wp.components.SelectControl;
	var ToggleControl        = wp.components.ToggleControl;
	var Button               = wp.components.Button;

	var WEAVIT_COLORS = [
		{ name: 'Navy', color: '#3B2350' },
		{ name: 'Navy Deep', color: '#2A1638' },
		{ name: 'Action', color: '#643486' },
		{ name: 'Action Dark', color: '#4A2364' },
		{ name: 'Charcoal', color: '#453E4C' },
		{ name: 'Mist', color: '#F7F3FA' },
		{ name: 'White', color: '#FFFFFF' },
	];

	/* ---------------------------------------------------------------- Button */
	registerBlockType( 'weavit/button', {
		apiVersion: 3,
		title: 'Button',
		description: 'A call-to-action button, styled to match the rest of the site.',
		icon: 'button',
		category: 'weavit',
		attributes: {
			text:   { type: 'string', default: 'Learn More' },
			url:    { type: 'string', default: '' },
			newTab: { type: 'boolean', default: false },
			style:  { type: 'string', default: 'primary' },
		},
		edit: function ( props ) {
			var a = props.attributes, setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'btn btn-' + a.style + ' px-7 py-3.5 text-base', style: { display: 'inline-block' } } );

			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Button Settings' },
						el( TextControl, { label: 'Link URL', value: a.url, onChange: function ( v ) { setAttributes( { url: v } ); } } ),
						el( ToggleControl, { label: 'Open in new tab', checked: a.newTab, onChange: function ( v ) { setAttributes( { newTab: v } ); } } ),
						el( SelectControl, {
							label: 'Style',
							value: a.style,
							options: [
								{ label: 'Primary', value: 'primary' },
								{ label: 'Outline', value: 'outline' },
								{ label: 'Dark', value: 'dark' },
							],
							onChange: function ( v ) { setAttributes( { style: v } ); },
						} )
					)
				),
				el( RichText, Object.assign( {}, blockProps, {
					tagName: 'span',
					value: a.text,
					onChange: function ( v ) { setAttributes( { text: v } ); },
					placeholder: 'Button text…',
					allowedFormats: [],
				} ) )
			);
		},
		save: function () { return null; },
	} );

	/* ---------------------------------------------------------------- Hero */
	registerBlockType( 'weavit/hero', {
		apiVersion: 3,
		title: 'Hero',
		description: 'An image + headline + description section, with a button underneath.',
		icon: 'cover-image',
		category: 'weavit',
		attributes: {
			title:           { type: 'string', default: '' },
			description:     { type: 'string', default: '' },
			layout:          { type: 'string', default: 'text-left' },
			desktopImageUrl: { type: 'string', default: '' },
			desktopImageId:  { type: 'number', default: 0 },
			mobileImageUrl:  { type: 'string', default: '' },
			mobileImageId:   { type: 'number', default: 0 },
			imageAlt:        { type: 'string', default: '' },
		},
		edit: function ( props ) {
			var a = props.attributes, setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'grid lg:grid-cols-2 gap-10 items-center py-10' } );

			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Hero Image' },
						el( MediaUploadCheck, {},
							el( MediaUpload, {
								onSelect: function ( media ) { setAttributes( { desktopImageUrl: media.url, desktopImageId: media.id, imageAlt: media.alt || a.imageAlt } ); },
								allowedTypes: [ 'image' ],
								value: a.desktopImageId,
								render: function ( obj ) {
									return el( Button, { onClick: obj.open, variant: 'secondary' }, a.desktopImageUrl ? 'Replace Desktop Image' : 'Select Desktop Image' );
								},
							} )
						),
						a.desktopImageUrl ? el( 'img', { src: a.desktopImageUrl, style: { maxWidth: '100%', marginTop: '8px', borderRadius: '4px' } } ) : null,
						el( MediaUploadCheck, {},
							el( MediaUpload, {
								onSelect: function ( media ) { setAttributes( { mobileImageUrl: media.url, mobileImageId: media.id } ); },
								allowedTypes: [ 'image' ],
								value: a.mobileImageId,
								render: function ( obj ) {
									return el( Button, { onClick: obj.open, variant: 'secondary', style: { marginTop: '8px' } }, a.mobileImageUrl ? 'Replace Mobile Image' : 'Select Mobile Image (optional)' );
								},
							} )
						),
						el( TextControl, { label: 'Image alt text', value: a.imageAlt, onChange: function ( v ) { setAttributes( { imageAlt: v } ); } } )
					),
					el( PanelBody, { title: 'Layout' },
						el( SelectControl, {
							label: 'Text alignment',
							value: a.layout,
							options: [
								{ label: 'Text left, image right', value: 'text-left' },
								{ label: 'Text right, image left', value: 'text-right' },
								{ label: 'Text centered', value: 'text-center' },
							],
							onChange: function ( v ) { setAttributes( { layout: v } ); },
						} )
					)
				),
				el( 'div', blockProps,
					a.desktopImageUrl
						? el( 'img', { src: a.desktopImageUrl, className: 'w-full h-auto rounded-xl object-cover' } )
						: el( 'div', { style: { background: '#F7F3FA', padding: '40px', textAlign: 'center', borderRadius: '12px', color: '#453E4C' } }, 'Select a desktop image in the sidebar →' ),
					el( 'div', {},
						el( RichText, { tagName: 'h2', className: 'text-3xl font-extrabold text-navy mb-4', value: a.title, onChange: function ( v ) { setAttributes( { title: v } ); }, placeholder: 'Hero title…' } ),
						el( RichText, { tagName: 'p', className: 'text-slate-600 mb-6', value: a.description, onChange: function ( v ) { setAttributes( { description: v } ); }, placeholder: 'Hero description…' } ),
						el( InnerBlocks, { allowedBlocks: [ 'weavit/button' ], template: [ [ 'weavit/button', {} ] ], templateLock: false } )
					)
				)
			);
		},
		save: function () { return el( InnerBlocks.Content ); },
	} );

	/* ---------------------------------------------------------------- Section */
	registerBlockType( 'weavit/section', {
		apiVersion: 3,
		title: 'Section',
		description: 'A full-width layout section — background color, optional anchor, holds other components.',
		icon: 'align-wide',
		category: 'weavit',
		attributes: {
			backgroundColor: { type: 'string', default: '' },
			fullWidth:       { type: 'boolean', default: true },
			anchorId:        { type: 'string', default: '' },
		},
		edit: function ( props ) {
			var a = props.attributes, setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { style: { backgroundColor: a.backgroundColor || undefined, padding: '24px', minHeight: '80px' } } );

			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Section Settings' },
						el( 'p', { style: { marginBottom: '8px', fontWeight: 600 } }, 'Background Color' ),
						el( ColorPalette, { colors: WEAVIT_COLORS, value: a.backgroundColor, onChange: function ( v ) { setAttributes( { backgroundColor: v || '' } ); } } ),
						el( ToggleControl, { label: 'Full width', checked: a.fullWidth, onChange: function ( v ) { setAttributes( { fullWidth: v } ); } } ),
						el( TextControl, { label: 'Anchor ID (optional)', value: a.anchorId, onChange: function ( v ) { setAttributes( { anchorId: v } ); }, help: 'For in-page links like #section-name' } )
					)
				),
				el( 'div', blockProps, el( InnerBlocks, { templateLock: false, template: [ [ 'weavit/hero', {} ] ] } ) )
			);
		},
		save: function () { return el( InnerBlocks.Content ); },
	} );
} )( window.wp );
