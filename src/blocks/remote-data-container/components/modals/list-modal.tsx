import { ItemListModal } from '@/blocks/remote-data-container/components/modals/item-list-modal';
import { useRemoteData } from '@/blocks/remote-data-container/hooks/use-remote-data';

interface ListModalProps {
	blockName: string;
	headerImage?: string;
	onSelect: ( data: RemoteDataQueryInput ) => void;
	queryKey: string;
	title: string;
}

export function ListModal( props: ListModalProps ) {
	const { blockName, onSelect, queryKey, title } = props;

	const { data, execute, loading } = useRemoteData( blockName, queryKey );

	return (
		<ItemListModal
			blockName={ blockName }
			buttonText="Select an item from a list"
			headerImage={ props.headerImage }
			loading={ loading }
			onOpen={ () => void execute( {} ) }
			onSelect={ onSelect }
			results={ data?.results }
			title={ title }
		/>
	);
}
