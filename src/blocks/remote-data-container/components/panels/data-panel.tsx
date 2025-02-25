import {
	__experimentalConfirmDialog as ConfirmDialog,
	Button,
	ButtonGroup,
	PanelBody,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

interface DataPanelProps {
	refreshRemoteData: () => void;
	remoteData: RemoteData;
	resetRemoteData: () => void;
}

export function DataPanel( props: DataPanelProps ) {
	const { refreshRemoteData, remoteData, resetRemoteData } = props;

	const [ isResetConfirmOpen, setResetConfirmOpen ] = useState< boolean >( false );

	function resetBlock(): void {
		resetRemoteData();
		setResetConfirmOpen( false );
	}

	if ( ! remoteData ) {
		return null;
	}

	return (
		<PanelBody title={ __( 'Remote data management', 'remote-data-blocks' ) }>
			<ButtonGroup>
				<Button
					onClick={ refreshRemoteData }
					style={ {
						marginRight: '10px',
					} }
					variant="primary"
				>
					{ __( 'Refresh', 'remote-data-blocks' ) }
				</Button>
				<Button
					isDestructive={ true }
					onClick={ () => setResetConfirmOpen( true ) }
					variant="secondary"
				>
					{ __( 'Reset block', 'remote-data-blocks' ) }
				</Button>
				{ isResetConfirmOpen && (
					<ConfirmDialog
						isOpen={ isResetConfirmOpen }
						onCancel={ () => setResetConfirmOpen( false ) }
						onConfirm={ resetBlock }
						style={ {
							maxWidth: '20em',
						} }
					>
						{ __(
							'Are you sure you want to reset the block? This will remove all remote data and reset the block to its initial state.',
							'remote-data-blocks'
						) }
					</ConfirmDialog>
				) }
			</ButtonGroup>
		</PanelBody>
	);
}
