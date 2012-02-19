/**
 * 
 */
package cbmarc.gwordpress.client.view;

import cbmarc.gwordpress.client.ProductsData;
import cbmarc.gwordpress.client.event.ContentEvent;
import cbmarc.gwordpress.client.event.EventBus;
import cbmarc.gwordpress.client.view.content.ProductsContentView;

import com.google.gwt.core.client.GWT;
import com.google.gwt.dom.client.SpanElement;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.ui.Anchor;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.Image;
import com.google.gwt.user.client.ui.Widget;

/**
 * @author Marc
 *
 */
public class ProductView extends Composite {
	
	private EventBus eventBus = EventBus.getEventBus();

	private static ProductViewUiBinder uiBinder = GWT
			.create(ProductViewUiBinder.class);

	interface ProductViewUiBinder extends UiBinder<Widget, ProductView> {
	}

	@UiField Anchor title;
	@UiField Anchor image;
	@UiField SpanElement content;
	
	@UiHandler(value={"title", "image"})
	void handleClick(ClickEvent e) {
		doLoad(e);
	}
	
	public ProductView(ProductsData entry) {
		String url = "#/?gwordpress=products&products=" + entry.getPostName();
		
		initWidget(uiBinder.createAndBindUi(this));
		
		title.setHTML(entry.getPostTitle() + " - " + entry.getPrice());
		title.setHref(url);
		
		Image i = new Image();
		i.setUrl(entry.getImage());
		image.setHref(url);
		image.getElement().appendChild(i.getElement());
		
		content.setInnerHTML(entry.getPostContent());
	}
	
	private void doLoad(ClickEvent e) {
		Anchor anchor = (Anchor)e.getSource();
		eventBus.fireEvent(new ContentEvent(
				new ProductsContentView(anchor.getHref())));
	}

}
