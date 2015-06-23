package at.ac.tuwien.sse.grosslicht;

import org.springframework.stereotype.Controller;
import org.springframework.ui.ModelMap;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;

@Controller
@RequestMapping("/")
public class MainController {

	@RequestMapping(method = RequestMethod.GET)
	public String index() {
		return "MainPage";
	}

  private String getSecret() {
    return "Das ist ein Geheimnis, niemand sollte das lesen können";
  }
	
}
