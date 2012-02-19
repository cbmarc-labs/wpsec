package cbmarc.gwordpress.client.event;

import com.google.gwt.event.shared.GwtEvent;

public class SingleProductsEvent extends GwtEvent<SingleProductsHandler> {
	public static final Type<SingleProductsHandler> TYPE = 
        new Type<SingleProductsHandler>();
	
	private String url = null;
	
	public SingleProductsEvent(String url) {
		this.url = url;
	}

	@Override
	protected void dispatch(SingleProductsHandler handler) {
		handler.onChange(url);
	}
	
	public static Type<SingleProductsHandler> getType() {
        return TYPE;
	}

	@Override
	public com.google.gwt.event.shared.GwtEvent.Type<SingleProductsHandler> getAssociatedType() {
		return getType();
	}

}
