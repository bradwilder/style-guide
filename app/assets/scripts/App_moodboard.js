require('./modules/jquery.globalstylesheet.js');
require('./modules/jquery.inputvalidator.js');
require('./modules/jquery.closest_descendent.js');
require('./modules/jquery.stickytableheaders.js');
require('./modules/jquery.tablehighlighter.js');
require('./modules/jquery.tablesorter.js');

import Menu from './modules/Menu';
import AjaxError from './modules/AjaxError';
import Logout from './modules/Logout';
import RefreshSession from './modules/RefreshSession';
import TableOfContents from './modules/TableOfContents';
import Popover from './modules/Popover';
import EditSection from './modules/EditSection';
import DeleteSection from './modules/DeleteSection';
import CommentReply from './modules/CommentReply';
import CommentSubmit from './modules/CommentSubmit';
import RemoveImage from './modules/RemoveImage';
import Forms from './modules/Forms';
import Modal from './modules/Modal';
import Modal_Arrange from './modules/Modal_Arrange';
import Modal_NewSection from './modules/Modal_NewSection';
import Modal_ManageImages from './modules/Modal_ManageImages';
import Modal_ArrangeSections from './modules/Modal_ArrangeSections';
import Modal_ArrangeMoodboardSectionImages from './modules/Modal_ArrangeMoodboardSectionImages';
import Modal_AddImages from './modules/Modal_AddImages';
import Validation from './modules/Validation';
import TableSorting from './modules/TableSorting';
import TableHeadersSticky from './modules/TableHeadersSticky';

var menu = new Menu();
var ajaxError = new AjaxError();
var logout = new Logout();
var refreshSession = new RefreshSession();
var tableOfContents = new TableOfContents();
var popover = new Popover();
var editSection = new EditSection();
var deleteSection = new DeleteSection();
var commentReply = new CommentReply();
var commentSubmit = new CommentSubmit();
var removeImage = new RemoveImage();
var forms = new Forms();
var modal = new Modal();
var modal_NewSection = new Modal_NewSection();
var modal_ManageImages = new Modal_ManageImages();
var modal_ArrangeMoodboardSectionImages = new Modal_ArrangeMoodboardSectionImages();
var modal_AddImages = new Modal_AddImages();
var validation = new Validation();
var tableSorting = new TableSorting();
var tableHeadersSticky = new TableHeadersSticky();
