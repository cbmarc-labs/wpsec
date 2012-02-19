/**
 * 
 */
package cbmarc.gwordpress.client;

import com.google.gwt.core.client.JsArray;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.http.client.URL;
import com.google.gwt.user.client.Window;

/**
 * @author Marc
 *
 */
public abstract class Dispatcher {
	
	//private String url = GWT.getHostPageBaseURL();
	private String url = "http://localhost/wordpress";

	private final native JsArray<ProductsData> getPostData( String json ) /*-{
		return eval( json );
	}-*/;
	
	public abstract void responseReceived(JsArray<ProductsData> postData);
	
	public void sendRequest(String url) {
		
		if (url.indexOf("#") > 0)
			url = url.substring(url.indexOf("#") + 1);
		
		RequestBuilder builder = new RequestBuilder(
				RequestBuilder.GET, URL.encode( this.url + url ));
		
		try {
			/*
		     * wait 5000 milliseconds for the request to complete
		     */
			builder.setTimeoutMillis(5000);
			builder.sendRequest(null, new RequestCallback() {

				@Override
				public void onResponseReceived( Request request,
						Response response ) {
					
					if (200 == response.getStatusCode()) {
						try {
							JsArray<ProductsData> postData = 
								getPostData( response.getText() );
							
							responseReceived(postData);
						} catch(Exception e) {
							Window.alert( e.toString() );
						}
					} else {
						Window.alert("Error with HTTP Request");
					}
					
				}

				@Override
				public void onError( Request request, Throwable exception ) {
					
					Window.alert( "Error with HTTP code: " + exception.getMessage() );
					
				}
				
			});
			
		} catch (RequestException e) {
			Window.alert( e.toString() );
		}
	}
}
