function init_rating(id_div,theme,NUMBER_OF_STARS){
    var ratings = document.getElementsByTagName('div');
    for (var i = 0; i < ratings.length; i++){
        if (ratings[i].className != 'rating_'+id_div)
            continue;

        var rating = ratings[i].firstChild.nodeValue;
        ratings[i].removeChild(ratings[i].firstChild);
        if (rating > NUMBER_OF_STARS || rating < 0)
            continue;
        for (var j = 0; j < NUMBER_OF_STARS; j++){
            var star = document.createElement('img');
            if (rating >= 1){
                star.setAttribute('title', j+1);
                star.setAttribute('style', 'vertical-align:middle');
                star.setAttribute('src', 'modules/Vote/images/stars/'+theme+'/rating_on.png');
                star.className = 'on';
                rating--;
            }else if(rating == 0.5){
                star.setAttribute('style', 'vertical-align:middle');
                star.setAttribute('src', 'modules/Vote/images/stars/'+theme+'/rating_half.png');
                star.className = 'half';
                rating = 0;
            }else{
                star.setAttribute('title', j+1);
                star.setAttribute('style', 'vertical-align:middle');
                star.setAttribute('src', 'modules/Vote/images/stars/'+theme+'/rating_off.png');
                star.className = 'off';
            }
            var widgetId = ratings[i].getAttribute('id').substr(7);
            star.setAttribute('id', 'star_'+widgetId+'_'+j);
            star.onmouseover = new Function("evt", "displayHover("+widgetId+", "+j+", "+theme+");");
            star.onmouseout = new Function("evt", "displayNormal("+widgetId+", "+j+", "+theme+");");
            ratings[i].appendChild(star);
        }
    }
}

function displayHover(ratingId, star, theme){
    for (var i = 0; i <= star; i++){
        document.getElementById('star_'+ratingId+'_'+i).setAttribute('src', 'modules/Vote/images/stars/'+theme+'/rating_over.png');
    }
}

function displayNormal(ratingId, star, theme){
    for (var i = 0; i <= star; i++){
        var status = document.getElementById('star_'+ratingId+'_'+i).className;
        document.getElementById('star_'+ratingId+'_'+i).setAttribute('src', 'modules/Vote/images/stars/'+theme+'/rating_'+status+'.png');
    }
}

function getXhr(){var f=null;if(window.XMLHttpRequest){f=new XMLHttpRequest()}else{if(window.ActiveXObject){try{f=new ActiveXObject("Msxml2.XMLHTTP")}catch(c){f=new ActiveXObject("Microsoft.XMLHTTP")}}else{alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");f=false}}return f}
