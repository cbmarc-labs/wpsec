package cbmarc.gwordpress.client.view.widget;

import com.google.gwt.core.client.GWT;
import com.google.gwt.dom.client.Element;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.Panel;
import com.google.gwt.user.client.ui.Widget;

public class WidgetView extends Composite {

	private static WidgetViewUiBinder uiBinder = GWT
			.create(WidgetViewUiBinder.class);

	interface WidgetViewUiBinder extends UiBinder<Widget, WidgetView> {
	}

	@UiField Element title;
	@UiField Panel container;

	public WidgetView(String title) {
		initWidget(uiBinder.createAndBindUi(this));
		
		this.title.setInnerHTML(title);
	}

}
