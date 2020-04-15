## Docs

- #### Installation

    - Clone git repository

    - Go to the project directory

    - Copy .env.example to .env

        `cp .env.example .env`
    
    - Change your database setting in the env file
    - Generate application key
    
        `php artisan key:generate`
      
    - Install dependencies
    
        `compose instsall`
        
    - Run migration
    
        `php artisan migrate`
    
    - Serve application
    
        `php artisan serve`

- #### Usage
     
     - Console command to check plots
        
        `php artisan check:cadastral cadNum1 cadNum2 ... cadNumN`
        
        `php artisan check:cadastral 69:27:0000022:1306 69:27:0000022:1307`

     - Api route for get all instance of checked plots
     
        `localhost:port/api/test/plots`
        
     - Run phpunit component test
     
        `php artisan test`

     - Web page route for component usage is
     
        `localhost:port/`

     - ##### PlotService api
          - public function checkPlots(string|array $cadNumbers) : string|Collection
          
               Return collection of PlotRegistry models or error string
               
               - Parameters
                    1. ***String|Array*** $cadNumbers
               - Return value
                    1. ***String***
                    2. ***Illuminate\Support\Collection***     
          - public function sendCheckRequest(array $cadNumbers): Response
               
               Send request to parser
               
               - Parameters
                    1. ***Array*** $cadNumbers
               - Return value
                    1. ***Illuminate\Http\Client\Response***
          - public function cadastralStringToArray($cadString): array
               
               Splitting input string to array of cadastral numbers
               
               - Parameters
                    1. ***String|Array*** $cadNumbers
               - Return value
                    1. ***Array*** 

          - public function getExistCadNums(array $cadNumbers) : array
               
               Return collection of exists models and array of cadastral numbers that should be checked
               
               - Parameters
                    1. ***Array*** $cadNumbers
               - Return value
                    1. ***Array [$cadExistModels, $cadNumbers]*** 

- #### Screenshots

     - #### Web page screenshot
    ![alt text](https://i.imgur.com/fr7868X.jpg)
     - #### Api return all database exists data 
    ![alt text](https://i.imgur.com/Hw9SPJO.jpg)
     - #### Console command output results
    ![alt text](https://i.imgur.com/o83C0zn.jpg)
     - #### PHPUnit tests
    ![alt text](https://i.imgur.com/2DhlR0s.jpg)
