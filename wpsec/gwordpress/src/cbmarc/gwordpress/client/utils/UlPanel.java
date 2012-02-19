package cbmarc.gwordpress.client.utils;

import com.google.gwt.dom.client.Document;
import com.google.gwt.user.client.ui.ComplexPanel;
import com.google.gwt.user.client.ui.Widget;

public class UlPanel extends ComplexPanel {
	
	public UlPanel() {
		setElement(Document.get().createULElement());
	}
	
	public void add(Widget w) {
		LiPanel li = new LiPanel(w);
		
		add(li, getElement());
	}

	private static class LiPanel extends ComplexPanel {
		
		protected LiPanel(Widget w) {
			setElement(Document.get().createLIElement());
			
			add(w, getElement());
		}
		
	}
	
}
