package cbmarc.gwordpress.client.event;

import com.google.gwt.event.shared.HandlerManager;

public class EventBus extends HandlerManager {
	
	private static EventBus instance = new EventBus();

	private EventBus() {
		super(null);
	}
	
	public static EventBus getEventBus() {
        return instance;
	}
}
