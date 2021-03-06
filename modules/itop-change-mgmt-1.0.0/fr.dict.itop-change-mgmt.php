<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * @author	Erwan Taloc <erwan.taloc@combodo.com>
 * @author	Romain Quetiez <romain.quetiez@combodo.com>
 * @author	Denis Flaven <denis.flaven@combodo.com>
 * @licence	http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:RoutineChange' => 'Ticket de Changement de Routine',
	'Class:RoutineChange+' => '',
	'Class:RoutineChange/Stimulus:ev_validate' => 'Valider',
	'Class:RoutineChange/Stimulus:ev_validate+' => '',
	'Class:RoutineChange/Stimulus:ev_reject' => 'Refuser',
	'Class:RoutineChange/Stimulus:ev_reject+' => '',
	'Class:RoutineChange/Stimulus:ev_assign' => 'Assigner',
	'Class:RoutineChange/Stimulus:ev_assign+' => '',
	'Class:RoutineChange/Stimulus:ev_reopen' => 'Réouvrir',
	'Class:RoutineChange/Stimulus:ev_reopen+' => '',
	'Class:RoutineChange/Stimulus:ev_plan' => 'Plannifier',
	'Class:RoutineChange/Stimulus:ev_plan+' => '',
	'Class:RoutineChange/Stimulus:ev_approve' => 'Approver',
	'Class:RoutineChange/Stimulus:ev_approve+' => '',
	'Class:RoutineChange/Stimulus:ev_replan' => 'Replannifier',
	'Class:RoutineChange/Stimulus:ev_replan+' => '',
	'Class:RoutineChange/Stimulus:ev_notapprove' => 'Ne pas approver',
	'Class:RoutineChange/Stimulus:ev_notapprove+' => '',
	'Class:RoutineChange/Stimulus:ev_implement' => 'Marquer comme effectué',
	'Class:RoutineChange/Stimulus:ev_implement+' => '',
	'Class:RoutineChange/Stimulus:ev_monitor' => 'Marquer comme suivi',
	'Class:RoutineChange/Stimulus:ev_monitor+' => '',
	'Class:RoutineChange/Stimulus:ev_finish' => 'Fermer',
	'Class:RoutineChange/Stimulus:ev_finish+' => '',
	'Class:NormalChange' => 'Ticket de Changement',
	'Class:NormalChange+' => '',
	'Class:NormalChange/Attribute:acceptance_date' => 'Date d\'acceptation',
	'Class:NormalChange/Attribute:acceptance_date+' => '',
	'Class:NormalChange/Attribute:acceptance_comment' => 'Commentaire sur l\'acceptation',
	'Class:NormalChange/Attribute:acceptance_comment+' => '',
	'Class:NormalChange/Stimulus:ev_validate' => 'Valider',
	'Class:NormalChange/Stimulus:ev_validate+' => '',
	'Class:NormalChange/Stimulus:ev_reject' => 'Rejeter',
	'Class:NormalChange/Stimulus:ev_reject+' => '',
	'Class:NormalChange/Stimulus:ev_assign' => 'Assigner',
	'Class:NormalChange/Stimulus:ev_assign+' => '',
	'Class:NormalChange/Stimulus:ev_reopen' => 'Réouvrir',
	'Class:NormalChange/Stimulus:ev_reopen+' => '',
	'Class:NormalChange/Stimulus:ev_plan' => 'Plannifier',
	'Class:NormalChange/Stimulus:ev_plan+' => '',
	'Class:NormalChange/Stimulus:ev_approve' => 'Approuver',
	'Class:NormalChange/Stimulus:ev_approve+' => '',
	'Class:NormalChange/Stimulus:ev_replan' => 'Replannifier',
	'Class:NormalChange/Stimulus:ev_replan+' => '',
	'Class:NormalChange/Stimulus:ev_notapprove' => 'Ne pas approuver',
	'Class:NormalChange/Stimulus:ev_notapprove+' => '',
	'Class:NormalChange/Stimulus:ev_implement' => 'Marquer comme effectué',
	'Class:NormalChange/Stimulus:ev_implement+' => '',
	'Class:NormalChange/Stimulus:ev_monitor' => 'Marquer comme suivi',
	'Class:NormalChange/Stimulus:ev_monitor+' => '',
	'Class:NormalChange/Stimulus:ev_finish' => 'Fermer',
	'Class:NormalChange/Stimulus:ev_finish+' => '',
	'Class:EmergencyChange' => 'Ticket de Changement d\'Urgence',
	'Class:EmergencyChange+' => '',
	'Class:EmergencyChange/Stimulus:ev_validate' => 'Valider',
	'Class:EmergencyChange/Stimulus:ev_validate+' => '',
	'Class:EmergencyChange/Stimulus:ev_reject' => 'Rejeter',
	'Class:EmergencyChange/Stimulus:ev_reject+' => '',
	'Class:EmergencyChange/Stimulus:ev_assign' => 'Assigner',
	'Class:EmergencyChange/Stimulus:ev_assign+' => '',
	'Class:EmergencyChange/Stimulus:ev_reopen' => 'Réouvrir',
	'Class:EmergencyChange/Stimulus:ev_reopen+' => '',
	'Class:EmergencyChange/Stimulus:ev_plan' => 'Plannifier',
	'Class:EmergencyChange/Stimulus:ev_plan+' => '',
	'Class:EmergencyChange/Stimulus:ev_approve' => 'Approuver',
	'Class:EmergencyChange/Stimulus:ev_approve+' => '',
	'Class:EmergencyChange/Stimulus:ev_replan' => 'Replannifier',
	'Class:EmergencyChange/Stimulus:ev_replan+' => '',
	'Class:EmergencyChange/Stimulus:ev_notapprove' => 'Ne pas approuver',
	'Class:EmergencyChange/Stimulus:ev_notapprove+' => '',
	'Class:EmergencyChange/Stimulus:ev_implement' => 'Marquer comme effectué',
	'Class:EmergencyChange/Stimulus:ev_implement+' => '',
	'Class:EmergencyChange/Stimulus:ev_monitor' => 'Marquer comme suivi',
	'Class:EmergencyChange/Stimulus:ev_monitor+' => '',
	'Class:EmergencyChange/Stimulus:ev_finish' => 'Fermer',
	'Class:EmergencyChange/Stimulus:ev_finish+' => '',
	'Menu:ChangeManagement' => 'Gestion des changements',
	'Menu:Change:Overview' => 'Vue d\'ensemble',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nouveau changement',
	'Menu:NewChange+' => 'Créer un nouveau ticket de changement',
	'Menu:SearchChanges' => 'Rechercher des changements',
	'Menu:SearchChanges+' => 'Rechercher parmi les tickets de changement',
	'Menu:Change:Shortcuts' => 'Raccourcis',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Tickets en attente d\'acceptance',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Tickets en attente d\'approbation',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Tickets ouverts',
	'Menu:Changes+' => 'Tickets de changement ouverts',
	'Menu:MyChanges' => 'Mes tickets',
	'Menu:MyChanges+' => 'Tickets de changement qui me sont assignés',
	'Class:Change' => 'Ticket de changement',
	'Class:Change+' => '',
	'Class:Change/Attribute:start_date' => 'Début de l\'intervention',
	'Class:Change/Attribute:start_date+' => '',
	'Class:Change/Attribute:status' => 'Etat',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Nouveau',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:validated' => 'Validé',
	'Class:Change/Attribute:status/Value:validated+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Rejeté',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Assigné',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:plannedscheduled' => 'Programmé',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Approuvé',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:notapproved' => 'Non approuvé',
	'Class:Change/Attribute:status/Value:notapproved+' => '',
	'Class:Change/Attribute:status/Value:implemented' => 'Effectué',
	'Class:Change/Attribute:status/Value:implemented+' => '',
	'Class:Change/Attribute:status/Value:monitored' => 'En cours de suivi',
	'Class:Change/Attribute:status/Value:monitored+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Fermé',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:reason' => 'Raison',
	'Class:Change/Attribute:reason+' => '',
	'Class:Change/Attribute:requestor_id' => 'Demandeur',
	'Class:Change/Attribute:requestor_id+' => '',
	'Class:Change/Attribute:requestor_email' => 'Demandeur',
	'Class:Change/Attribute:requestor_email+' => '',
	'Class:Change/Attribute:org_id' => 'Client',
	'Class:Change/Attribute:org_id+' => '',
	'Class:Change/Attribute:org_name' => 'Client',
	'Class:Change/Attribute:org_name+' => '',
	'Class:Change/Attribute:workgroup_id' => 'Groupe de travail',
	'Class:Change/Attribute:workgroup_id+' => '',
	'Class:Change/Attribute:workgroup_name' => 'Groupe de travail',
	'Class:Change/Attribute:workgroup_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Date de création',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:last_update' => 'Dernière mise à jour',
	'Class:Change/Attribute:last_update+' => '',
	'Class:Change/Attribute:end_date' => 'Date de fin',
	'Class:Change/Attribute:end_date+' => '',
	'Class:Change/Attribute:close_date' => 'Date de fermeture',
	'Class:Change/Attribute:close_date+' => '',
	'Class:Change/Attribute:impact' => 'Impact',
	'Class:Change/Attribute:impact+' => '',
	'Class:Change/Attribute:agent_id' => 'Agent',
	'Class:Change/Attribute:agent_id+' => '',
	'Class:Change/Attribute:agent_name' => 'Agent',
	'Class:Change/Attribute:agent_name+' => '',
	'Class:Change/Attribute:agent_email' => 'Agent',
	'Class:Change/Attribute:agent_email+' => '',
	'Class:Change/Attribute:supervisor_group_id' => 'Equipe de supervision',
	'Class:Change/Attribute:supervisor_group_id+' => '',
	'Class:Change/Attribute:supervisor_group_name' => 'Equipe de supervision',
	'Class:Change/Attribute:supervisor_group_name+' => '',
	'Class:Change/Attribute:supervisor_id' => 'Superviseur',
	'Class:Change/Attribute:supervisor_id+' => '',
	'Class:Change/Attribute:supervisor_email' => 'Superviseur',
	'Class:Change/Attribute:supervisor_email+' => '',
	'Class:Change/Attribute:manager_group_id' => 'Equipe responsable',
	'Class:Change/Attribute:manager_group_id+' => '',
	'Class:Change/Attribute:manager_group_name' => 'Equipe responsable',
	'Class:Change/Attribute:manager_group_name+' => '',
	'Class:Change/Attribute:manager_id' => 'Responsable',
	'Class:Change/Attribute:manager_id+' => '',
	'Class:Change/Attribute:manager_email' => 'Responsable',
	'Class:Change/Attribute:manager_email+' => '',
	'Class:Change/Attribute:outage' => 'Interruption de service',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Oui',
	'Class:Change/Attribute:outage/Value:yes+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Non',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:change_request' => 'Demande',
	'Class:Change/Attribute:change_request+' => '',
	'Class:Change/Attribute:fallback' => 'Plan de secours',
	'Class:Change/Attribute:fallback+' => '',
	'Class:Change/Stimulus:ev_validate' => 'Valider',
	'Class:Change/Stimulus:ev_validate+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Rejeter',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Assigner',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Réouvrir',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Plannifier',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Approuver',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_replan' => 'Replannifier',
	'Class:Change/Stimulus:ev_replan+' => '',
	'Class:Change/Stimulus:ev_notapprove' => 'Ne pas approuver',
	'Class:Change/Stimulus:ev_notapprove+' => '',
	'Class:Change/Stimulus:ev_implement' => 'Marquer comme effectué',
	'Class:Change/Stimulus:ev_implement+' => '',
	'Class:Change/Stimulus:ev_monitor' => 'Marquer comme suivi',
	'Class:Change/Stimulus:ev_monitor+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Fermer',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Class:RoutineChange/Attribute:status/Value:new' => 'Nouveau',
	'Class:RoutineChange/Attribute:status/Value:new+' => '',
	'Class:RoutineChange/Attribute:status/Value:assigned' => 'Assigné',
	'Class:RoutineChange/Attribute:status/Value:assigned+' => '',
	'Class:RoutineChange/Attribute:status/Value:plannedscheduled' => 'Programmé',
	'Class:RoutineChange/Attribute:status/Value:plannedscheduled+' => '',
	'Class:RoutineChange/Attribute:status/Value:approved' => 'Approuvé',
	'Class:RoutineChange/Attribute:status/Value:approved+' => '',
	'Class:RoutineChange/Attribute:status/Value:implemented' => 'Effectué',
	'Class:RoutineChange/Attribute:status/Value:implemented+' => '',
	'Class:RoutineChange/Attribute:status/Value:monitored' => 'En cours de suivi',
	'Class:RoutineChange/Attribute:status/Value:monitored+' => '',
	'Class:RoutineChange/Attribute:status/Value:closed' => 'Fermé',
	'Class:RoutineChange/Attribute:status/Value:closed+' => '',
	'Class:ApprovedChange' => 'Changements approuvés',
	'Class:ApprovedChange+' => '',
	'Class:ApprovedChange/Attribute:approval_date' => 'Date d\'approbation',
	'Class:ApprovedChange/Attribute:approval_date+' => '',
	'Class:ApprovedChange/Attribute:approval_comment' => 'Commentaire sur l\'approbation',
	'Class:ApprovedChange/Attribute:approval_comment+' => '',
	'Class:ApprovedChange/Stimulus:ev_validate' => 'Valider',
	'Class:ApprovedChange/Stimulus:ev_validate+' => '',
	'Class:ApprovedChange/Stimulus:ev_reject' => 'Rejeter',
	'Class:ApprovedChange/Stimulus:ev_reject+' => '',
	'Class:ApprovedChange/Stimulus:ev_assign' => 'Assigner',
	'Class:ApprovedChange/Stimulus:ev_assign+' => '',
	'Class:ApprovedChange/Stimulus:ev_reopen' => 'Réouvrir',
	'Class:ApprovedChange/Stimulus:ev_reopen+' => '',
	'Class:ApprovedChange/Stimulus:ev_plan' => 'Plannifier',
	'Class:ApprovedChange/Stimulus:ev_plan+' => '',
	'Class:ApprovedChange/Stimulus:ev_approve' => 'Approuver',
	'Class:ApprovedChange/Stimulus:ev_approve+' => '',
	'Class:ApprovedChange/Stimulus:ev_replan' => 'Replannifier',
	'Class:ApprovedChange/Stimulus:ev_replan+' => '',
	'Class:ApprovedChange/Stimulus:ev_notapprove' => 'Ne pas approuver',
	'Class:ApprovedChange/Stimulus:ev_notapprove+' => '',
	'Class:ApprovedChange/Stimulus:ev_implement' => 'Marquer comme effectué',
	'Class:ApprovedChange/Stimulus:ev_implement+' => '',
	'Class:ApprovedChange/Stimulus:ev_monitor' => 'Marquer comme suivi',
	'Class:ApprovedChange/Stimulus:ev_monitor+' => '',
	'Class:ApprovedChange/Stimulus:ev_finish' => 'Fermer',
	'Class:ApprovedChange/Stimulus:ev_finish+' => '',
	'Class:NormalChange/Attribute:status/Value:new' => 'Nouveau',
	'Class:NormalChange/Attribute:status/Value:new+' => '',
	'Class:NormalChange/Attribute:status/Value:validated' => 'Validé',
	'Class:NormalChange/Attribute:status/Value:validated+' => '',
	'Class:NormalChange/Attribute:status/Value:rejected' => 'Rejeté',
	'Class:NormalChange/Attribute:status/Value:rejected+' => '',
	'Class:NormalChange/Attribute:status/Value:assigned' => 'Assigné',
	'Class:NormalChange/Attribute:status/Value:assigned+' => '',
	'Class:NormalChange/Attribute:status/Value:plannedscheduled' => 'Programmé',
	'Class:NormalChange/Attribute:status/Value:plannedscheduled+' => '',
	'Class:NormalChange/Attribute:status/Value:approved' => 'Approuvé',
	'Class:NormalChange/Attribute:status/Value:approved+' => '',
	'Class:NormalChange/Attribute:status/Value:notapproved' => 'Non approuvé',
	'Class:NormalChange/Attribute:status/Value:notapproved+' => '',
	'Class:NormalChange/Attribute:status/Value:implemented' => 'Effectué',
	'Class:NormalChange/Attribute:status/Value:implemented+' => '',
	'Class:NormalChange/Attribute:status/Value:monitored' => 'En cours de suivi',
	'Class:NormalChange/Attribute:status/Value:monitored+' => '',
	'Class:NormalChange/Attribute:status/Value:closed' => 'Fermé',
	'Class:NormalChange/Attribute:status/Value:closed+' => '',
	'Class:EmergencyChange/Attribute:status/Value:new' => 'Nouveau',
	'Class:EmergencyChange/Attribute:status/Value:new+' => '',
	'Class:EmergencyChange/Attribute:status/Value:validated' => 'Validé',
	'Class:EmergencyChange/Attribute:status/Value:validated+' => '',
	'Class:EmergencyChange/Attribute:status/Value:rejected' => 'Rejeté',
	'Class:EmergencyChange/Attribute:status/Value:rejected+' => '',
	'Class:EmergencyChange/Attribute:status/Value:assigned' => 'Assigné',
	'Class:EmergencyChange/Attribute:status/Value:assigned+' => '',
	'Class:EmergencyChange/Attribute:status/Value:plannedscheduled' => 'Programmé',
	'Class:EmergencyChange/Attribute:status/Value:plannedscheduled+' => '',
	'Class:EmergencyChange/Attribute:status/Value:approved' => 'Approuvé',
	'Class:EmergencyChange/Attribute:status/Value:approved+' => '',
	'Class:EmergencyChange/Attribute:status/Value:notapproved' => 'Non approuvé',
	'Class:EmergencyChange/Attribute:status/Value:notapproved+' => '',
	'Class:EmergencyChange/Attribute:status/Value:implemented' => 'Effectué',
	'Class:EmergencyChange/Attribute:status/Value:implemented+' => '',
	'Class:EmergencyChange/Attribute:status/Value:monitored' => 'En cours de suivi',
	'Class:EmergencyChange/Attribute:status/Value:monitored+' => '',
	'Class:EmergencyChange/Attribute:status/Value:closed' => 'Fermé',
	'Class:EmergencyChange/Attribute:status/Value:closed+' => '',
));
?>
