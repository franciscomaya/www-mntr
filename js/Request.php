<?require_once '../classes/_Var.php';$Var        =_Var::getInstance();  $Var->__autoload("Script"); $Script=Script::getInstance();       $Script->object("Request");$Script->attribute("VDataAll:new Array(new Array()),");$Script->attribute("IntCounterSet:0,");$Script->attribute("IntRowCounterSet:0,");$Script->attribute("IntColumnCounterSet:0,");$Script->attribute("getVecDataAll:new Array(),");$Script->attribute("setVecDataAll:new Array(),");$Script->initialize();$Script->destroyAttribute();$Script->method("setVar","DATA,_var",                "this.VDataAll[this.IntRowCounterSet-this.IntCounterSet][this.IntColumnCounterSet+this.IntCounterSet] = DATA;                 if(this.value(_var)==null)this.VDataAll[this.IntRowCounterSet-this.IntCounterSet][this.IntColumnCounterSet+1+this.IntCounterSet] = '';				    else this.VDataAll[this.IntRowCounterSet-this.IntCounterSet][this.IntColumnCounterSet+1+this.IntCounterSet] = this.value(_var);				         this.IntCounterSet      =this.IntCounterSet+1;                 this.IntRowCounterSet   =this.IntRowCounterSet+1;                 this.IntColumnCounterSet=this.IntColumnCounterSet+1; ",true);$Script->method("getVar","Data",                "for(var IntRow=0;IntRow<this.VDataAll.length;IntRow++){       		  	  		      for(var IntColumn=0;IntColumn<this.VDataAll[IntRow].length;IntColumn++){              		  	     	  this.getVecDataAll.push(this.VDataAll[IntRow][IntColumn-1]);                               if(IntColumn>0){               		  	     	     while(this.getVecDataAll.toString()){       		  				  	   	  	       if(this.getVecDataAll.shift()==Data)       		  				  		     	          return this.VDataAll[IntRow][IntColumn];                 				  		  	          }/*End while*/              				    	       }/*End if*/              		  			   }/*End for*/              	 			   }/*End for*/",true);$Script->method("value","name",                 'return $("#"+name+"").val();',true);$Script->method("valueId","name,id",                 'return $("#"+name+""+id+"").val();',true);$Script->method("setValue","name,value,dbled",                 'if(dbled)$("#"+name+"").attr("disabled", true);				 return $("#"+name+"").val(""+value+"");',true);						$Script->method("html","name,html",                 '$("#"+name+"").html(html);',true);$Script->method("htmlClean","name",                 '$("#"+name+"").html("");',true);                $Script->method("validateForm","name",                 'return $("#"+name+"").validate().form();',true);$Script->method("clean","name",                 '$("#"+name+"").val("");');				$Script->endObject();$Var->release($Script,$Var);                     ?>