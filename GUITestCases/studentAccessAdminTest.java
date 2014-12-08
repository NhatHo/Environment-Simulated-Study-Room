package com.example.tests;

import java.util.regex.Pattern;
import java.util.concurrent.TimeUnit;
import org.junit.*;
import static org.junit.Assert.*;
import static org.hamcrest.CoreMatchers.*;
import org.openqa.selenium.*;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.support.ui.Select;

public class Test {
  private WebDriver driver;
  private String baseUrl;
  private boolean acceptNextAlert = true;
  private StringBuffer verificationErrors = new StringBuffer();

  @Before
  public void setUp() throws Exception {
    driver = new FirefoxDriver();
    baseUrl = "http://localhost:9444/";
    driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
  }

  @Test
  public void test() throws Exception {
    driver.get(baseUrl + "/Environment-Simulated-Study-Room/index.html");
    driver.findElement(By.id("loginUsername")).clear();
    driver.findElement(By.id("loginUsername")).sendKeys("student");
    driver.findElement(By.id("loginPassword")).clear();
    driver.findElement(By.id("loginPassword")).sendKeys("CarletonStudent");
    driver.findElement(By.id("login")).click();
	Thread.sleep (3000);
	driver.get(baseUrl + "/Environment-Simulated-Study-Room/admin.html");
	assertTrue (driver.getCurrentUrl().contains("/client.html"));
	Thread.sleep (3000);
	driver.get(baseUrl + "/Environment-Simulated-Study-Room/addScene.html");
	assertTrue (driver.getCurrentUrl().contains("/client.html"));
	Thread.sleep (3000);
	driver.get(baseUrl + "/Environment-Simulated-Study-Room/editScene.html");
	assertTrue (driver.getCurrentUrl().contains("/client.html"));
	Thread.sleep (3000);
	driver.get(baseUrl + "/Environment-Simulated-Study-Room/deleteScene.html");
	assertTrue (driver.getCurrentUrl().contains("/client.html"));
  }

  @After
  public void tearDown() throws Exception {
    driver.quit();
    String verificationErrorString = verificationErrors.toString();
    if (!"".equals(verificationErrorString)) {
      fail(verificationErrorString);
    }
  }

  private boolean isElementPresent(By by) {
    try {
      driver.findElement(by);
      return true;
    } catch (NoSuchElementException e) {
      return false;
    }
  }

  private boolean isAlertPresent() {
    try {
      driver.switchTo().alert();
      return true;
    } catch (NoAlertPresentException e) {
      return false;
    }
  }

  private String closeAlertAndGetItsText() {
    try {
      Alert alert = driver.switchTo().alert();
      String alertText = alert.getText();
      if (acceptNextAlert) {
        alert.accept();
      } else {
        alert.dismiss();
      }
      return alertText;
    } finally {
      acceptNextAlert = true;
    }
  }
}
