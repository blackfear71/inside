/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ouvre la zone de saisie d'une phrase culte
  $('#afficherSaisiePhraseCulte').click(function()
  {
    // Initialisation des titres de la saisie
    initialisationSaisie('zone_saisie_collector', 'T');

    // Affichage de la zone de saisie
    afficherMasquerIdWithDelay('zone_saisie_collector');
  });

  // Ferme la zone de saisie d'une phrase culte
  $('#fermerSaisiePhraseCulte').click(function()
  {
    // Réinitialisation de la saisie
    resetSaisie('T');

    // Fermeture de l'affichage
    afficherMasquerIdWithDelay('zone_saisie_collector');
  });

  // Ouvre la zone de saisie d'une image culte
  $('#afficherSaisieImageCulte').click(function()
  {
    // Initialisation des titres de la saisie
    initialisationSaisie('zone_saisie_image', 'I');

    // Affichage de la zone de saisie
    afficherMasquerIdWithDelay('zone_saisie_image');
  });

  // Ferme la zone de saisie d'une image culte
  $('#fermerSaisieImageCulte').click(function()
  {
    // Réinitialisation de la saisie
    resetSaisie('I');

    // Fermeture de l'affichage
    afficherMasquerIdWithDelay('zone_saisie_image');
  });

  // Ouvre la fenêtre de saisie d'une phrase / image culte en modification
  $('.modifierCollector').click(function()
  {
    var idCollector = $(this).attr('id').replace('modifier_collector_', '');

    initialisationModification(idCollector);
  });

  // Réinitialise la saisie à la fermeture au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme la saisie d'une dépense
    if ($(event.target).attr('class') == 'fond_saisie')
    {
      switch (event.target.id)
      {
        case 'zone_saisie_collector':
          resetSaisie('T');
          break;

        case 'zone_saisie_image':
          resetSaisie('I');
          break;

        default:
          break;
      }
    }
  });

  // Ouvre la zone de saisie de vote
  $('.afficherSaisieVote').click(function()
  {
    var idCollector = $(this).attr('id').replace('link_form_vote_', '');

    // Initialisation du vote
    initialisationVote(idCollector);
  });

  // Ferme la zone de saisie de vote
  $('#fermerSaisieVote').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_vote');
  });

  // Ouvre la zone de visualisation des votes
  $('.afficherDetailsVotes').click(function()
  {
    var idCollector = $(this).attr('id').replace('link_details_votes_', '');

    // Affichage détail des votes
    showVotes(idCollector);
  });

  // Ferme la zone de visualisation des votes
  $('#fermerDetailsVotes').click(function()
  {
    afficherMasquerIdWithDelay('zone_details_votes');
  });

  // Bloque la saisie en cas de soumission (phrase culte)
  $('#validerSaisiePhraseCulte').click(function()
  {
    var idForm          = $('#zone_saisie_collector');
    var zoneForm        = 'zone_contenu_saisie';
    var zoneContenuForm = 'contenu_saisie';

    blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
  });

  // Bloque la saisie en cas de soumission (image culte)
  $('#validerSaisieImageCulte').click(function()
  {
    var idForm          = $('#zone_saisie_image');
    var zoneForm        = 'zone_contenu_saisie';
    var zoneContenuForm = 'contenu_saisie';

    blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
  });

  // Bloque la saisie en cas de soumission (vote utilisateur)
  $('.validerSaisieVoteCollector').click(function()
  {
    var idForm          = $('#zone_saisie_vote');
    var zoneForm        = 'zone_contenu_saisie';
    var zoneContenuForm = 'contenu_saisie';

    blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
  });

  /*** Actions au changement ***/
  // Applique les filtres
  $('#applySort, #applyFilter').on('change', function()
  {
    if ($(this).val() == 'dateDesc' || $(this).val() == 'dateAsc')
      applySortOrFilter($(this).val(), $_GET('filter'));
    else
      applySortOrFilter($_GET('sort'), $(this).val());
  });

  // Charge l'image dans la zone de saisie
  $('.loadSaisieCollector').on('change', function(event)
  {
    loadFile(event, 'image_collector', true);
  });

  // Affiche la saisie "Autre" (phrase culte)
  $('#speaker').on('change', function()
  {
    afficherOther('speaker', 'other_name');
  });

  // Affiche la saisie "Autre" (image culte)
  $('#speaker_2').on('change', function()
  {
    afficherOther('speaker_2', 'other_name_2');
  });
});

// Au chargement du document complet
$(window).on('load', function()
{
  // Déclenchement du scroll
  var id     = $_GET('anchor');
  var offset = 0.1;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);
});

/*****************/
/*** Fonctions ***/
/*****************/
// Affiche ou masque la zone de saisie "Autre"
function afficherOther(select, required)
{
  if ($('#' + select).val() == 'other')
  {
    $('#' + required).css('display', 'block');
    $('#' + required).prop('required', true);
  }
  else
  {
    $('#' + required).css('display', 'none');
    $('#' + required).prop('required', false);
  }
}

// Redirige pour appliquer le tri ou le filtre
function applySortOrFilter(sort, filter)
{
  document.location.href = 'collector.php?action=goConsulter&page=1&sort=' + sort + '&filter=' + filter;
}

// Initialisation des titres de la saisie d'une phrase / image culte
function initialisationSaisie(idZone, typeCollector)
{
  // Initialisations
  var titre;
  var sousTitre;

  // Titre et sous-titre
  if (typeCollector == 'I')
  {
    titre = 'Saisir une image culte';
    sousTitre = 'L\'image culte';
  }
  else
  {
    titre = 'Saisir une phrase culte';
    sousTitre = 'La phrase culte';
  }

  // Modification des données
  $('#' + idZone).find('.zone_titre_saisie').html(titre);
  $('#' + idZone).find('.texte_titre_section').html(sousTitre);
}

// Initialisation du vote d'une phrase / image culte
function initialisationVote(idCollector)
{
  // Récupération des données
  var collector     = listeCollectors[idCollector];
  var voteUser      = collector['vote_user'];
  var typeCollector = collector['type_collector'];
  var titre;

  // Titre
  if (typeCollector == 'I')
    titre = 'Voter pour une image culte';
  else
    titre = 'Voter pour une phrase culte';

  // Modification des données
  $('.zone_titre_saisie').html(titre);
  $('input[name=id_collector]').val(idCollector);

  $('.smiley').each(function()
  {
    var numeroSmiley = $(this).attr('name').replace('smiley_', '');

    if (numeroSmiley == voteUser)
      $(this).addClass('smiley_selected');
    else
      $(this).removeClass('smiley_selected');
  });

  // Affiche la zone de saisie
  afficherMasquerIdWithDelay('zone_saisie_vote');
}

// Affiche la zone de mise à jour d'une phrase / image culte
function initialisationModification(idCollector)
{
  // Récupération des données
  var collector     = listeCollectors[idCollector];
  var typeCollector = collector['type_collector'];
  var dateCollector = formatDateForDisplayMobile(collector['date_collector']);
  var speaker       = collector['speaker'];
  var typeSpeaker   = collector['type_speaker'];
  var contenu       = collector['collector'];
  var contexte      = collector['context'];
  var action;
  var titre;
  var sousTitre;

  // Action du formulaire
  action = 'collector.php?sort=' + $_GET('sort') + '&filter=' + $_GET('filter') + '&action=doModifierMobile';

  // Titre et sous-titre
  if (typeCollector == 'I')
  {
    titre = 'Modifier une image culte';
    sousTitre = 'L\'image culte';
  }
  else
  {
    titre = 'Modifier une phrase culte';
    sousTitre = 'La phrase culte';
  }

  // Modification des données
  if (typeCollector == 'I')
  {
    // Action du formulaire
    $('#zone_saisie_image').find('.form_saisie').attr('action', action);

    // Titre et sous-titre
    $('#zone_saisie_image').find('.zone_titre_saisie').html(titre);
    $('#zone_saisie_image').find('.texte_titre_section').html(sousTitre);

    // Identifiant image culte
    $('#zone_saisie_image').find('#id_saisie_image').val(idCollector);

    // Speaker et champ "Autre"
    if (typeSpeaker == 'other')
    {
      $('#zone_saisie_image').find('.saisie_speaker').val(typeSpeaker);
      $('#zone_saisie_image').find('.saisie_other_collector').val(speaker);

      afficherOther('speaker_2', 'other_name_2');
    }
    else
      $('#zone_saisie_image').find('.saisie_speaker').val(speaker);

    // Date
    $('#zone_saisie_image').find('.saisie_date_collector').val(dateCollector);

    // Contenu
    $('#zone_saisie_image').find('#image_collector').attr('src', '../../includes/images/collector/' + contenu);
    $('#zone_saisie_image').find('.bouton_parcourir_image').prop('required', false);

    // Contexte
    $('#zone_saisie_image').find('.saisie_contexte').html(contexte);
  }
  else
  {
    // Action du formulaire
    $('#zone_saisie_collector').find('.form_saisie').attr('action', action);

    // Titre et sous-titre
    $('#zone_saisie_collector').find('.zone_titre_saisie').html(titre);
    $('#zone_saisie_collector').find('.texte_titre_section').html(sousTitre);

    // Identifiant phrase culte
    $('#zone_saisie_collector').find('#id_saisie_collector').val(idCollector);

    // Speaker et champ "Autre"
    if (typeSpeaker == 'other')
    {
      $('#zone_saisie_collector').find('.saisie_speaker').val(typeSpeaker);
      $('#zone_saisie_collector').find('.saisie_other_collector').val(speaker);

      afficherOther('speaker', 'other_name');
    }
    else
      $('#zone_saisie_collector').find('.saisie_speaker').val(speaker);

    // Date
    $('#zone_saisie_collector').find('.saisie_date_collector').val(dateCollector);

    // Contenu
    $('#zone_saisie_collector').find('.saisie_collector').html(contenu);

    // Contexte
    $('#zone_saisie_collector').find('.saisie_contexte').html(contexte);
  }

  // Affiche la zone de saisie
  if (typeCollector == 'I')
    afficherMasquerIdWithDelay('zone_saisie_image');
  else
    afficherMasquerIdWithDelay('zone_saisie_collector');
}

// Réinitialise la zone de saisie d'une phrase / image culte si fermeture modification
function resetSaisie(typeCollector)
{
  // Déclenchement après la fermeture
  setTimeout(function()
  {
    // Test si action = modification
    var currentAction;

    if (typeCollector == 'I')
      currentAction = $('#zone_saisie_image').find('.form_saisie').attr('action').split('&action=');
    else
      currentAction = $('#zone_saisie_collector').find('.form_saisie').attr('action').split('&action=');

    var call = currentAction[currentAction.length - 1]

    if (call == 'doModifierMobile')
    {
      // Action du formulaire
      var action = 'collector.php?action=doAjouterMobile&page=' + $_GET('page');

      $('#zone_saisie_collector').find('.form_saisie').attr('action', action);
      $('#zone_saisie_image').find('.form_saisie').attr('action', action);

      // Initialisation des titres des saisies
      initialisationSaisie('zone_saisie_collector', 'T');
      initialisationSaisie('zone_saisie_image', 'I');

      // Identifiants phrase / image culte
      $('#zone_saisie_collector').find('#id_saisie_collector').val('');
      $('#zone_saisie_image').find('#id_saisie_image').val('');

      // Champs "Autre"
      $('#zone_saisie_collector').find('.saisie_speaker').val('');
      $('#zone_saisie_collector').find('.saisie_other_collector').val('');
      $('#zone_saisie_collector').find('.saisie_other_collector').css('display', 'none');

      $('#zone_saisie_image').find('.saisie_speaker').val('');
      $('#zone_saisie_image').find('.saisie_other_collector').val('');
      $('#zone_saisie_image').find('.saisie_other_collector').css('display', 'none');

      // Date
      $('#zone_saisie_collector').find('.saisie_date_collector').val('');
      $('#zone_saisie_image').find('.saisie_date_collector').val('');

      // Contenu
      $('#zone_saisie_collector').find('.saisie_collector').html('');

      $('#zone_saisie_image').find('#image_collector').attr('src', '');
      $('#zone_saisie_image').find('.bouton_parcourir_image').prop('required', true);

      // Contexte
      $('#zone_saisie_collector').find('.saisie_contexte').html('');
      $('#zone_saisie_image').find('.saisie_contexte').html('');
    }
  }, 200);
}

// Affichage du détail des votes d'une phrase / image culte
function showVotes(idCollector)
{
  // Récupération des données
  var collector     = listeCollectors[idCollector];
  var listeVotes    = collector['votes'];
  var typeCollector = collector['type_collector'];
  var nombreLignesTotal     = Object.keys(listeVotes).length;
  var nombreLignesCourantes = 0;
  var html = '';
  var pseudos;
  var titre;

  // Titre
  if (typeCollector == 'I')
    titre = 'Votes de l\'image culte';
  else
    titre = 'Votes de la phrase culte';

  // Modification des données
  $('.texte_titre_section').html(titre);

  $.each(listeVotes, function(smiley, users)
  {
    nombreLignesCourantes++;

    html += '<div class="zone_details_smiley">';
      html += '<img src="../../includes/icons/common/smileys/' + smiley + '.png" alt="smiley" class="smiley_details" />';

      html += '<div class="zone_details_pseudos">';
        pseudos = '';

        $.each(users, function(key, pseudo)
        {
          pseudos += pseudo + ', ';
        });

        html += pseudos.slice(0, -2);
      html += '</div>';
    html += '</div>';

    if (nombreLignesCourantes < nombreLignesTotal)
      html += '<div class="separation_details"></div>';
  });

  $('.zone_details_votes_users').html(html);

  // Affiche la zone de détails
  afficherMasquerIdWithDelay('zone_details_votes');
}
