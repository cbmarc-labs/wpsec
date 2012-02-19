package cbmarc.gwordpress.client;

import com.google.gwt.core.client.EntryPoint;
import com.google.gwt.user.client.ui.RootPanel;

/**
 * Entry point classes define <code>onModuleLoad()</code>.
 */
public class Gwordpress implements EntryPoint {
	
	private AppController appController;
	
	/**
	 * This is the entry point method.
	 */
	public void onModuleLoad() {
		appController = new AppController();
		appController.go(RootPanel.get());
	}
}
