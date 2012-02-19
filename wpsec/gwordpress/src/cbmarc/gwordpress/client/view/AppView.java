package cbmarc.gwordpress.client.view;

import com.google.gwt.core.client.GWT;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.HasWidgets;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.Panel;
import com.google.gwt.user.client.ui.Widget;

public class AppView extends Composite {

	private static AppViewUiBinder uiBinder = GWT
			.create(AppViewUiBinder.class);

	interface AppViewUiBinder extends UiBinder<Widget, AppView> {
	}

	public AppView() {
		initWidget(uiBinder.createAndBindUi(this));
		
		search.add(new Label("Just another WordPress site"));
	}

	@UiField Panel search;
	@UiField Panel header;
	@UiField Panel menu;
	@UiField Panel sidebar;
	@UiField Panel content;
	@UiField Panel footer;
		
	public HasWidgets getContentPanel() {
		return content;
	}
	
	public HasWidgets getSidebarPanel() {
		return sidebar;
	}
}
