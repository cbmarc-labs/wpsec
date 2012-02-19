package cbmarc.gwordpress.client.view.content;

import cbmarc.gwordpress.client.Dispatcher;
import cbmarc.gwordpress.client.ProductsData;
import cbmarc.gwordpress.client.view.ProductView;

import com.google.gwt.core.client.JsArray;

public class ProductsContentView extends ContentView {

	public ProductsContentView(String url) {
		
		Dispatcher dispatcher = new Dispatcher() {

			@Override
			public void responseReceived(JsArray<ProductsData> postData) {
				setData(postData);
			}
			
		};
		
		dispatcher.sendRequest(url);
	}
	
	public void setData(JsArray<ProductsData> data) {
		for (int i = 0; i < data.length(); i++) {
			ProductsData entry = data.get( i );
			
			content.add(new ProductView(entry));
		}
	}

}
