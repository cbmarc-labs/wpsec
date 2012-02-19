package cbmarc.gwordpress.client.view.widget;

import cbmarc.gwordpress.client.Dispatcher;
import cbmarc.gwordpress.client.ProductsData;
import cbmarc.gwordpress.client.event.ContentEvent;
import cbmarc.gwordpress.client.event.EventBus;
import cbmarc.gwordpress.client.utils.UlPanel;
import cbmarc.gwordpress.client.view.content.ProductsContentView;

import com.google.gwt.core.client.JsArray;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.user.client.ui.Anchor;

public class CategoryWidgetView extends WidgetView {

	private EventBus eventBus = EventBus.getEventBus();
	
	public CategoryWidgetView(String url) {
		super("PRODUCTS CATEGORY");
		
		Dispatcher dispatcher = new Dispatcher() {

			@Override
			public void responseReceived(JsArray<ProductsData> postData) {
				setData(postData);
			}
			
		};
		
		dispatcher.sendRequest(url);
	}
	
	public void setData(JsArray<ProductsData> data) {
		UlPanel ulPanel = new UlPanel();
		
		for (int i = 0; i < data.length(); i++) {
			ProductsData entry = data.get( i );
			
			Anchor anchor = new Anchor(
					entry.getName(), false, 
					"#/?gwordpress=products&products_category=" + entry.getSlug());
			
			ulPanel.add(anchor);
			
			anchor.addClickHandler(new ClickHandler() {

				@Override
				public void onClick(ClickEvent event) {
					Anchor anchor = (Anchor)event.getSource();
					
					eventBus.fireEvent(new ContentEvent(
							new ProductsContentView(anchor.getHref())));
				}
				
			});
			
			container.add(ulPanel);
		}
	}

}
