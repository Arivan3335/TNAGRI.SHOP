import { addFilter } from '@wordpress/hooks'

//import { useState, useRef, useEffect } from 'react';

import { useState } from 'react';


import { InspectorControls } from '@wordpress/block-editor'

import { PanelBody, PanelRow, TextareaControl, TextControl } from '@wordpress/components'

import { __ } from '@wordpress/i18n'

import { createHigherOrderComponent } from '@wordpress/compose'

addFilter(
	'blocks.registerBlockType',
	'tailybymdz/blocks-taily-settings',

	(settings, name) => {

		/*if(name === 'core/button'){
			return settings;
		}*/

		return {
			...settings,

			attributes: {

				...settings.attributes,

				tailyCustomCss: {
					type: 'string',
					default: '',
				},

				tailyCustomTag: {
					type: 'string',
					default: '',
				},

			},
		};

	},
)


function Edit(props){
	
	const [ text, setText ] = useState( '' );


	const takeAndSendValue = (value) => {

		//console.log(props)

		setText( value )

		props.setAttributes({
			tailyCustomCss: value,
		})

		/*Apply Live CSS*/
		props.setAttributes({
            className: value !== '' ? value : undefined
        });
		/***/
	}

	const saveTextVal = (value) => {
		
		setText( value )

		props.setAttributes({
			tailyCustomTag: value,
		})
	}

	return (
		<InspectorControls>
            <PanelBody title={__("Taily CSS Classes")}>
				<PanelRow>
					<TextareaControl 
					label={__("Custom Css")}
					className={"taily-textarea-cssinp"}
					help={__("Note: enter your custom css here! (Suitable for Tailwinds's long class-names)")}
					value={ props.attributes.tailyCustomCss }
					onChange={takeAndSendValue}
					cols="100"
					/>
				</PanelRow>

				<PanelRow>
				    <TextControl 
					label={__("Apply on which tag?")}
					className={"taily-textinp-tagname"}
					placeholder={__("example: p")}
					help={__("Note: If you want your CSS classes to apply to an inner tag, please enter the tag name")}
					value={ props.attributes.tailyCustomTag }
					onChange={saveTextVal}
				    />
				</PanelRow>

			</PanelBody>
		</InspectorControls>
	)
}
addFilter(
	'editor.BlockEdit',
	'tailybymdz/blocks-taily-settings',

	createHigherOrderComponent((BlockEdit) => {
		return (props) => {
			return (
				<>
				    <Edit {...props} />
				    <BlockEdit {...props} />
				</>
	        )
		}
	})
)



const withCustomClassName = createHigherOrderComponent( ( BlockListBlock ) => {
    return ( props ) => {
        if(props.attributes.size) {
            return <BlockListBlock { ...props } className={ "mdzblock-" + props.attributes.size } />;
        } else {
            return <BlockListBlock {...props} />
        }

    };
}, 'withClientIdClassName' );

addFilter( 
	'editor.BlockListBlock', 
	'tailybymdz/blocks-taily-settings',
	withCustomClassName 
);

//console.log("Worked-Here!")