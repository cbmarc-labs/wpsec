package cbmarc.gwordpress.client;

import cbmarc.gwordpress.client.event.ContentEvent;
import cbmarc.gwordpress.client.event.ContentHandler;
import cbmarc.gwordpress.client.event.EventBus;
import cbmarc.gwordpress.client.view.AppView;
import cbmarc.gwordpress.client.view.content.ProductsContentView;
import cbmarc.gwordpress.client.view.widget.CategoryWidgetView;
import cbmarc.gwordpress.client.view.widget.RecentWidgetView;

import com.google.gwt.user.client.ui.HasWidgets;
import com.google.gwt.user.client.ui.Widget;

public class AppController implements ContentHandler {
	
	private EventBus eventBus = EventBus.getEventBus();
	private AppView appView;
	
	public AppController() {
		eventBus.addHandler(ContentEvent.getType(), this);
		
		appView = new AppView();
	}
	
	public void go(final HasWidgets container) {
		appView.getSidebarPanel().add(
				new RecentWidgetView("/?gwordpress=products&post_type=products"));
		appView.getSidebarPanel().add(
				new CategoryWidgetView("/?gwordpress=categories"));
		
		eventBus.fireEvent(new ContentEvent(
				new ProductsContentView("/?gwordpress=products&post_type=products")));
		
		container.add(appView);
	}

	@Override
	public void onChange(Widget widget) {
		appView.getContentPanel().clear();
		appView.getContentPanel().add(widget);
	}
}
