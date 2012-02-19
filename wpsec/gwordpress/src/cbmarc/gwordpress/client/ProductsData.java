package cbmarc.gwordpress.client;

import com.google.gwt.core.client.JavaScriptObject;

public class ProductsData extends JavaScriptObject {
	
	protected ProductsData() {}
	
	// JSNI methods to get stock data.
	public final native String getPostTitle() /*-{ return this.post_title; }-*/;
	public final native String getPostName() /*-{ return this.post_name; }-*/;
	public final native String getName() /*-{ return this.name; }-*/;
	public final native String getPrice() /*-{ return this.price; }-*/;
	public final native String getImage() /*-{ return this.image; }-*/;
	public final native String getPostContent() /*-{ return this.post_content; }-*/;
	public final native String getSlug() /*-{ return this.slug; }-*/;

}
