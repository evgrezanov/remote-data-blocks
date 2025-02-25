import { CheckboxControl, SelectControl } from '@wordpress/components';

import { TEXT_FIELD_TYPES } from '@/blocks/remote-data-container/config/constants';

interface BlockBindingFieldControlProps {
	availableBindings: AvailableBindings;
	fieldTypes: string[];
	label: string;
	target: string;
	updateFieldBinding: ( target: string, field: string ) => void;
	value: string;
}

export function BlockBindingFieldControl( props: BlockBindingFieldControlProps ) {
	const { availableBindings, fieldTypes, label, target, updateFieldBinding, value } = props;
	const options = Object.entries( availableBindings )
		.filter( ( [ _key, mapping ] ) => fieldTypes.includes( mapping.type ) )
		.map( ( [ key, mapping ] ) => {
			return { label: mapping.name, value: key };
		} );

	return (
		<SelectControl
			label={ label }
			name={ target }
			options={ [ { label: 'Select a field', value: '' }, ...options ] }
			onChange={ ( field: string ) => updateFieldBinding( target, field ) }
			value={ value }
		/>
	);
}

interface BlockBindingControlsProps {
	attributes: RemoteDataInnerBlockAttributes;
	availableBindings: AvailableBindings;
	blockName: string;
	removeBinding: ( target: string ) => void;
	updateBinding: ( target: string, args: Omit< RemoteDataBlockBindingArgs, 'block' > ) => void;
}

export function BlockBindingControls( props: BlockBindingControlsProps ) {
	const { attributes, availableBindings, blockName, removeBinding, updateBinding } = props;
	const contentArgs = attributes.metadata?.bindings?.content?.args;
	const contentField = contentArgs?.field ?? '';
	const imageAltField = attributes.metadata?.bindings?.image_alt?.args?.field ?? '';
	const imageUrlField = attributes.metadata?.bindings?.image_url?.args?.field ?? '';

	function updateFieldBinding( target: string, field: string ): void {
		if ( ! field ) {
			removeBinding( target );
			return;
		}

		const args = attributes.metadata?.bindings?.[ target ]?.args ?? {};
		updateBinding( target, { ...args, field } );
	}

	function updateFieldLabel( showLabel: boolean ): void {
		if ( ! contentField ) {
			// Form input should be disabled in this state, but check anyway.
			return;
		}

		const label = showLabel
			? Object.entries( availableBindings ).find( ( [ key ] ) => key === contentField )?.[ 1 ]?.name
			: undefined;
		updateBinding( 'content', { ...contentArgs, field: contentField, label } );
	}

	switch ( blockName ) {
		case 'core/heading':
		case 'core/paragraph':
			return (
				<>
					<BlockBindingFieldControl
						availableBindings={ availableBindings }
						fieldTypes={ TEXT_FIELD_TYPES }
						label="Content"
						target="content"
						updateFieldBinding={ updateFieldBinding }
						value={ contentField }
					/>
					<CheckboxControl
						checked={ Boolean( contentArgs?.label ) }
						disabled={ ! contentField }
						label="Show label"
						name="show_label"
						onChange={ updateFieldLabel }
					/>
				</>
			);

		case 'core/image':
			return (
				<>
					<BlockBindingFieldControl
						availableBindings={ availableBindings }
						fieldTypes={ [ 'image_url' ] }
						label="Image URL"
						target="image_url"
						updateFieldBinding={ updateFieldBinding }
						value={ imageUrlField }
					/>
					<BlockBindingFieldControl
						availableBindings={ availableBindings }
						fieldTypes={ [ 'image_alt' ] }
						label="Image alt text"
						target="image_alt"
						updateFieldBinding={ updateFieldBinding }
						value={ imageAltField }
					/>
				</>
			);
	}

	return null;
}
