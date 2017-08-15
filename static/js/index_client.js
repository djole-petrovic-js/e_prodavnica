$(document).ready(function(){

  'use strict';

  $('#btnVote').on('click',function(){
    var id = $('#pollUL input:checked').attr('value');

    if ( !id ) {
      return alert('Morate da izaberete jednu od ponudjenih opcija');
    }

    $('#btnVote').hide();
    $('#btnShowVotes').hide();
    
    $.ajax({
      method:'POST',
      url:'/client_api/vote.php',
      data:{
        id:id
      },
      dataType:'json'
    }).done(function(response){
      if ( response.alreadyVoted === true ) {
        alert('Vec ste glasali...');
      }

      if ( response.userDoesntExist === true ) {
        alert('Morate biti ulogovan da biste glasali , prikazani su vam rezultati ankete');
      }

      displayPollResults();      
    }).catch(function(error){
      alert('Doslo je do greske...');
    });
  });

  $('#btnShowVotes').on('click',function(){
    $('#btnVote').hide();
    $('#btnShowVotes').hide();

    displayPollResults();
  });

  function displayPollResults() {
    $.getJSON('/api/get_poll.php',function(response){
      var resultHTML = '';
      var sum = 0;

      response.forEach(function(option){
        sum += +option.broj_glasova;
      });

      response.forEach(function(option){
        var percent = Math.round(
          option.broj_glasova / sum * 100
        );

        resultHTML += `
          <li class="list-group-item">
            ${ option.opcija } : ${ option.broj_glasova } ${ percent } %
          </li>
        `;
      });

      $('#pollUL').html(resultHTML);
    });
  }

});
