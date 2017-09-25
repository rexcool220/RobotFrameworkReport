*** Settings ***
Test Template    MyTest
Library           Selenium2Library

*** Test Cases ***
case1    @
case2    org
case3    com

*** Keywords ***
MyTest
    [Arguments]    ${InputText_id__email}
    Open Browser    http://localhost:8080/SeleniumWeb/account/search    Chrome
    Maximize Browser Window
    Input Text    id=email    ${InputText_id__email}
    Click Button    id=searchBtn
    Set Selenium Speed    1
    Capture Page Screenshot    filename=selenium-screenshot-{index}.png
    Close Browser
