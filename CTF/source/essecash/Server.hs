-- compile with ghc -threaded Server.hs -o server
{-# LANGUAGE OverloadedStrings #-}
module Main where

import Logic
import Control.Monad    (msum,void)
import Control.Monad.Trans ( liftIO )
import Happstack.Server
import           Text.Blaze ((!))
import qualified Text.Blaze.Html4.Strict as H
import qualified Text.Blaze.Html4.Strict.Attributes as A
import Control.Concurrent.MVar

appTemplate :: String -> [H.Html] -> H.Html -> H.Html
appTemplate title headers body =
    H.html $ do
      H.head $ do
        H.title (H.toHtml title)
        H.meta ! A.httpEquiv "Content-Type"
               ! A.content "text/html;charset=utf-8"
        H.link ! A.href "/static/essecss/bootstrap.css"
               ! A.rel "stylesheet"
        H.link ! A.href "/static/essecss/esse_webshop.css"
               ! A.rel "stylesheet"              
        H.link ! A.href "/static/essecss/bootstrap-theme.css"
               ! A.rel "stylesheet"
        sequence_ headers
      H.body $ do
        body

helloBlaze :: ServerPart Response
helloBlaze =
      ok $ toResponse $
       appTemplate "Hello, Blaze!"
                [H.meta ! A.name "keywords"
                        ! A.content "happstack, blaze, html"
                ]
                (H.div ! A.class_ "container" $ do
                          H.div ! A.class_ "row" $ do
			        H.h1 "EsseCoin service"
                          H.div ! A.class_ "row" $ do
                                H.div ! A.class_ "col-md-4" $ do
                                      H.img ! A.src "/static/essecoin.png"
                                H.div ! A.class_ "col-md-8" $ do
                                  "This is the famous essecoin service which lets you store your esse coins, "
                                  "it does not look fancy yet because all the effort was used make it secure"
                                  "Haskell was used to get maximal security, because functional programs can't have security issues"
			          H.br
                                  H.a "list" ! A.href "/list"
			          H.br
			          H.a "create" ! A.href "/create"
			          H.br
			          H.a "query" ! A.href "/query"
			          H.br
                          )

queryForm  :: ServerPart Response
queryForm =
   ok $ toResponse $
       appTemplate "Query!"
                []
                (do
		   H.h1 "Query a known value"
		   H.form  ! A.action "/querypost" ! A.method "get" ! A.class_ "form-horizontal" $ do
			      H.div ! A.class_ "form-group" $ do
			       H.label "Planned usage" ! A.for "title" ! A.class_ "col-sm-2 control-label"
                               H.div ! A.class_ "col-sm-10" $ do 
                                H.input ! A.type_ "text" ! A.name "title" ! A.id "title" ! A.class_ "form-control" ! A.size "20"
			      H.div ! A.class_ "form-group" $ do
			       H.label "Password" ! A.for "secret" ! A.class_ "col-sm-2 control-label"
                               H.div ! A.class_ "col-sm-10" $ do 
                                H.input ! A.type_ "text" ! A.name "secret" ! A.id "secret" ! A.class_ "form-control" ! A.size "20"
			      H.div ! A.class_ "form-group" $ do
			       H.div ! A.class_ "col-sm-offset-2 col-sm-10" $ do
			        H.input ! A.type_ "submit" ! A.class_ "btn btn-default"
		)   

queryPost :: MVar () -> ServerPart Response
queryPost lock =
   do title <- look "title"
      secret <- look "secret"
      entry <- liftIO $ withMVar lock $ \_ -> secGet title secret

      ok $ toResponse $
       appTemplate "Results!"
                []
                (do
                    H.h1 "Results"
                    H.p $ do
			H.toHtml entry
                    H.a "index" ! A.href "/"
                )


createForm :: ServerPart Response
createForm =
   ok $ toResponse $
       appTemplate "Create!"
                []
                (do
		   H.h1 "EsseCoin Service"
		   H.div ! A.class_ "well" $ do  "Here you can add your essecoins protected with a password and a description how to spend them" 
		   H.form  ! A.action "/createpost" ! A.method "get" ! A.class_ "form-horizontal" $ do
			      H.div ! A.class_ "form-group" $ do
			       H.label "Planned usage" ! A.for "usage" ! A.class_ "col-sm-2 control-label"
                               H.div ! A.class_ "col-sm-10" $ do 
                                H.input ! A.type_ "text" ! A.name "usage" ! A.id "usage" ! A.class_ "form-control" ! A.size "20"
			      H.div ! A.class_ "form-group" $ do
			       H.label "Password" ! A.for "password" ! A.class_ "col-sm-2 control-label"
                               H.div ! A.class_ "col-sm-10" $ do 
                                H.input ! A.type_ "text" ! A.name "password" ! A.id "password" ! A.class_ "form-control" ! A.size "20"
			      H.div ! A.class_ "form-group" $ do
			       H.label "EsseCoin" ! A.for "secret" ! A.class_ "col-sm-2 control-label"
                               H.div ! A.class_ "col-sm-10" $ do 
                                H.input ! A.type_ "text" ! A.name "secret" ! A.id "secret" ! A.class_ "form-control" ! A.size "20"
			      H.div ! A.class_ "form-group" $ do
			       H.div ! A.class_ "col-sm-offset-2 col-sm-10" $ do
			        H.input ! A.type_ "submit" ! A.class_ "btn btn-default"
		)   

createBlaze :: MVar () -> ServerPart Response
createBlaze lock =
   do name <- look "usage"
      title <- look "password"
      secret <- look "secret"
      liftIO $ withMVar lock $ \_ -> secAdd name title secret

      ok $ toResponse $
       appTemplate "Create!"
                []
                (H.p $ do "Created "
			  H.toHtml name
                          H.div $ do
                            H.a "back to the index" ! A.href "/")

format :: [String] -> H.Html
format (a:b:xs) =
	H.li ! A.class_ "list-group-item" $ do
           H.div ! A.class_ "row" $ do
            H.div ! A.class_ "col-xs-2"  $ do
             H.toHtml a 
            H.div ! A.class_ "col-xs-2" $ do
             H.toHtml b 

listBlaze :: MVar() -> ServerPart Response
listBlaze lock =
 do entries <- liftIO $ withMVar lock $ \_ -> secList
    ok $ toResponse $
       appTemplate "The List!"
                []
                (do
                           H.h1 "List of known entries, "
		           H.ul $ do 
                            sequence_ $ map format entries
                           H.a "index" ! A.href "/")     
main :: IO ()
main = do
   lock <- newMVar ()
   simpleHTTP nullConf $ msum 
	[dir "createpost" $createBlaze lock
	,dir "create" $createForm
        ,dir "list" $ listBlaze lock
        ,dir "query" $ queryForm
        ,dir "querypost" $ queryPost lock
        ,dir "static" $ serveDirectory DisableBrowsing ["index.html"] "/home/landing/web/"
	, helloBlaze
	]


