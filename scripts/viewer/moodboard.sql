INSERT INTO mb_image (name, description) VALUES ('Droid Sans Mono', 'Droid Sans Mono font');
set @droid_sans_mono_id := last_insert_id();

INSERT INTO mb_image (name, description) VALUES ('Source Sans Pro', 'Source Sans Pro font');
set @source_sans_pro_id := last_insert_id();

INSERT INTO mb_image (name, description) VALUES ('Newsweek font', 'Newsweek photo attribution font');
set @newsweek_font_id := last_insert_id();

INSERT INTO mb_image (name, description) VALUES ('Navigation 1', 'Example of navigation');
set @navigation_id := last_insert_id();

INSERT INTO mb_image (name, description) VALUES ('Facebook navigation', 'Example of navigation from Facebook');
set @facebook_navigation_id := last_insert_id();

INSERT INTO mb_image (name, description) VALUES ('Photoshop controls 1', 'Photoshop controls 1');
set @photoshop_1_id := last_insert_id();

INSERT INTO mb_image (name, description) VALUES ('Photoshop controls 2', 'Photoshop controls 2');
set @photoshop_2_id := last_insert_id();

INSERT INTO mb_image (name, description) VALUES ('Fixed table header', 'Example of fixed table header, taken from https://codepen.io/nikhil8krishnan/full/WvYPvv/');
set @table_header_id := last_insert_id();

INSERT INTO mb_image (name, description) VALUES ('iTunes table selection', 'Example of table selection table from iTunes');
set @table_selection_id := last_insert_id();

select @row_mode_id := id from mb_mode where code = 'row';
select @sm_size_id := id from mb_size where code = 'sm';

INSERT INTO mb_section (name, description, modeID, position) VALUES ('Fonts', 'Font families and typography', @row_mode_id, 1);
set @section_id := last_insert_id();

INSERT INTO mb_section_image (sectionID, imageID, position, sizeID) VALUES (@section_id, @droid_sans_mono_id, 1, @sm_size_id);
INSERT INTO mb_section_image (sectionID, imageID, position, sizeID) VALUES (@section_id, @source_sans_pro_id, 2, @sm_size_id);
INSERT INTO mb_section_image (sectionID, imageID, position, sizeID) VALUES (@section_id, @newsweek_font_id, 3, @sm_size_id);

INSERT INTO mb_section (name, description, modeID, position) VALUES ('Tables', 'Table selection/highlighting, sorting, headers, etc', @row_mode_id, 2);
set @section_id := last_insert_id();

INSERT INTO mb_section_image (sectionID, imageID, position, sizeID) VALUES (@section_id, @table_header_id, 1, @sm_size_id);
INSERT INTO mb_section_image (sectionID, imageID, position, sizeID) VALUES (@section_id, @table_selection_id, 2, @sm_size_id);

INSERT INTO mb_section (name, description, modeID, position) VALUES ('Navigation', 'Menus and naviation elements', @row_mode_id, 3);
set @section_id := last_insert_id();

INSERT INTO mb_section_image (sectionID, imageID, position, sizeID) VALUES (@section_id, @facebook_navigation_id, 1, @sm_size_id);
INSERT INTO mb_section_image (sectionID, imageID, position, sizeID) VALUES (@section_id, @navigation_id, 2, @sm_size_id);

INSERT INTO mb_section (name, description, modeID, position) VALUES ('UI elements', 'UI elements and form controls', @row_mode_id, 4);
set @section_id := last_insert_id();

INSERT INTO mb_section_image (sectionID, imageID, position, sizeID) VALUES (@section_id, @photoshop_1_id, 1, @sm_size_id);
INSERT INTO mb_section_image (sectionID, imageID, position, sizeID) VALUES (@section_id, @photoshop_2_id, 2, @sm_size_id);
