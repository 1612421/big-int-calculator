# Table of Contents

1. [Information of the project](#info)
2. [My work](#my-work)
3. [Code structure](#code-structure)
4. [Request handling process](#request-workflow)

## 1. <a name="info"></a> Information of the project
- github: https://github.com/1612421/big-int-calculator
- host: https://inf-int-calculator.herokuapp.com/

## 2. <a name="my-work"></a> My work
- Analyze the requirements
- Write down features that the project will have 
- Design UI and APIs
- Design components such as class, interface, abstract, etc. Then I will develop BE and complete APIs
- Develop FE base on UI mock before
- Call API and fill data to UI

## 3. <a name="code-structure"></a> Code structure
![Class diagram](docs/ClassDiagram.png?raw=true "Class diagram")

## 4. a name="request-workflow"></a>Request handling process
- The request via route component come to middleware
- Middleware check CSRF token and forward to next layer, if fail, stop and response
- After the middlewares, the request will come to controller
- The controller will be created and call the function which was mapped with url
- In the function handle the request:
    - Validate calculation string and get the numbers and the operator by regex
    - Call the "calculate" function of mCalculatorService
    - response result
