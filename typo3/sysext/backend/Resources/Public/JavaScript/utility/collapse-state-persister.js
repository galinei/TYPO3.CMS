/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
import{Collapse as BootstrapCollapse}from"bootstrap";import Client from"@typo3/backend/storage/client.js";import DocumentService from"@typo3/core/document-service.js";import RegularEvent from"@typo3/core/event/regular-event.js";export class CollapseStatePersister{constructor(){this.localStorageKey="collapse-states-",this.localStorageKeyDefaultSuffix="general",this.searchValueSelector=".t3js-collapse-search-term",this.searchField=null,this.searchForm=null,DocumentService.ready().then((()=>{this.searchField=document.querySelector(this.searchValueSelector),null!==this.searchField&&(this.searchForm=this.searchField.closest("form"),this.searchField.value=Client.get(this.searchField.dataset.persistCollapseSearchKey)??""),this.registerEventListener(),this.recoverStates()}))}registerEventListener(){const e='.collapse[data-persist-collapse-state="true"]';new RegularEvent("show.bs.collapse",(e=>{const t=e.target;"true"===t.dataset.persistCollapseState&&(null!==this.searchField&&""===this.searchField.value||void 0===t.dataset.persistCollapseStateNotIfSearch)&&this.toStorage(t,!0)})).delegateTo(document,e),new RegularEvent("hide.bs.collapse",(e=>{const t=e.target;"true"===t.dataset.persistCollapseState&&(null!==this.searchField&&""===this.searchField.value||void 0===t.dataset.persistCollapseStateNotIfSearch)&&this.toStorage(t,!1)})).delegateTo(document,e),null!==this.searchForm&&new RegularEvent("submit",(e=>{e.preventDefault(),null!==this.searchField&&""===this.searchField.value&&this.recoverStates()})).bindTo(this.searchForm)}recoverStates(){document.querySelectorAll('.collapse[data-persist-collapse-state="true"]').forEach((e=>{const t=e.dataset.persistCollapseStateSuffix??this.localStorageKeyDefaultSuffix,s=this.fromStorage(t),a=e.id;if(""===a||!this.shallRecoverState(e))return;const l=BootstrapCollapse.getOrCreateInstance(e,{toggle:!1}),r="shown"===(e.dataset.persistCollapseStateIfState??"shown"),o="hidden"===(e.dataset.persistCollapseStateIfState??"hidden");!0===r&&(!0===s[a]?l.show():l.hide()),!0===o&&(!1===s[a]?l.hide():l.show())}))}shallRecoverState(e){return void 0===e.dataset.persistCollapseStateNotIfSearch||"false"===e.dataset.persistCollapseStateNotIfSearch||null!==this.searchField&&""===this.searchField.value}fromStorage(e){const t=Client.get(this.localStorageKey+e);return null===t?{}:JSON.parse(t)}toStorage(e,t){const s=e.id,a=e.dataset.persistCollapseStateSuffix??this.localStorageKeyDefaultSuffix,l=this.fromStorage(a),r="shown"===(e.dataset.persistCollapseStateIfState??"shown"),o="hidden"===(e.dataset.persistCollapseStateIfState??"hidden");!0===t&&!0===r&&!0!==l[s]&&(l[s]=!0,Client.set(this.localStorageKey+a,JSON.stringify(l))),!0===t&&!0===o&&!1===l[s]&&(delete l[s],Client.set(this.localStorageKey+a,JSON.stringify(l))),!1===t&&!0===o&&!1!==l[s]&&(l[s]=!1,Client.set(this.localStorageKey+a,JSON.stringify(l))),!1===t&&!0===r&&!0===l[s]&&(delete l[s],Client.set(this.localStorageKey+a,JSON.stringify(l)))}}export default new CollapseStatePersister;