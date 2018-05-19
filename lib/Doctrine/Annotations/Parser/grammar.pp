%pragma lexer.unicode 1

%skip   space              [\x20\x09\x0a\x0d]+
%token  doc_               /\*\*                      -> docblock

%skip   docblock:space     [\x20\x09\x0a\x0d]+
%skip   docblock:star      \*(?!/)
%token  docblock:_doc      \*/                         -> default
%token  docblock:at        @(?!\s)                     -> annot
%token  docblock:text      .+

%token  annot:valued_identifier [\\]?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*(?=\()
%token  annot:simple_identifier [\\]?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)* -> __shift__
%token  annot:parenthesis_  \(                          -> value

%skip   value:star         [*]
%skip   value:_doc         [*/]
%skip   value:space        [\x20\x09\x0a\x0d]+
%token  value:_parenthesis \)                          -> __shift__ * 2
%token  value:at           @(?!\s)                     -> annot
%token  value:comma        ,
%token  value:brace_       {
%token  value:_brace       }
%token  value:double_colon ::
%token  value:colon        :
%token  value:equals       =
%token  value:quote_       "                           -> string
%token  value:null         \bnull\b
%token  value:boolean      \b(?:true|false)\b
%token  value:number       \-?(0|[1-9]\d*)(\.\d+)?([eE][\+\-]?\d+)?
%token  value:identifier   [a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*

%token  string:string      (?:[^"\\]+|(\\\\)*\\"|(\\\\)+|\\?[^"\\]+)+
%token  string:_quote      "                           -> __shift__

#docblock:
    ::doc_::
    (::text:: | annotation())*
    ::_doc::

#annotation:
    ::at::
    (
        <simple_identifier>
        | ( <valued_identifier> ::parenthesis_:: ( parameters() )? ::_parenthesis:: )
    )

#values:
    value() ( ::comma:: value() )* ::comma::?

#list:
    ::brace_:: ( (value() ( ::comma:: value() )*) ::comma::? )? ::_brace::

#map:
    ::brace_:: pairs() ::comma::? ::_brace::

#pairs:
    pair() ( ::comma:: pair() )*

#pair:
    (<identifier> | string() | <number> | constant()) ( ::equals:: | ::colon:: ) value()

#value:
    <null> | <boolean> | string() | <number> | pair() | map() | list() | annotation() | constant()

parameters:
    values() | string()

#constant:
    <identifier> (::double_colon:: <identifier>)?

#string:
    ::quote_:: <string>? ::_quote::
