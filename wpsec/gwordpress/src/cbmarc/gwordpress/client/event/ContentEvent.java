package cbmarc.gwordpress.client.event;

import com.google.gwt.event.shared.GwtEvent;
import com.google.gwt.user.client.ui.Widget;

public class ContentEvent extends GwtEvent<ContentHandler> {
	public static final Type<ContentHandler> TYPE = 
        new Type<ContentHandler>();
	
	private Widget widget = null;
	
	public ContentEvent(Widget w) {
		widget = w;
	}

	@Override
	protected void dispatch(ContentHandler handler) {
		handler.onChange(widget);
	}
	
	public static Type<ContentHandler> getType() {
        return TYPE;
	}

	@Override
	public com.google.gwt.event.shared.GwtEvent.Type<ContentHandler> getAssociatedType() {
		return getType();
	}

}
