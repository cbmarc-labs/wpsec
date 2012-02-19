package cbmarc.gwordpress.client.event;

import com.google.gwt.event.shared.EventHandler;
import com.google.gwt.user.client.ui.Widget;

public interface ContentHandler extends EventHandler {
	public void onChange(Widget widget);
}
