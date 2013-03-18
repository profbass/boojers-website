/**
 * Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

// This file contains style definitions that can be used by CKEditor plugins.
//
// The most common use for it is the "stylescombo" plugin, which shows a combo
// in the editor toolbar, containing all styles. Other plugins instead, like
// the div plugin, use a subset of the styles on their feature.
//
// If you don't have plugins that depend on this file, you can simply ignore it.
// Otherwise it is strongly recommended to customize this file to match your
// website requirements and design properly.

CKEDITOR.stylesSet.add( 'default', [
	{ name: 'Italic Title',		element: 'h2', attributes: { 'class': 'italic' } },
	{ name: 'Subtitle',			element: 'h3', attributes: { 'class': 'italic gray' } },
	{ name: 'Well', 			element: 'div', attributes: { 'class': 'well' } },
	{ name: 'Centered Text', 	element: 'div', attributes: { 'class': 'text-center' } },
	{ name: 'Right Aligned Text', 		element: 'div', attributes: { 'class': 'text-right' } },

	{ name: 'Lead Body Copy', 		element: 'p', attributes: { 'class': 'lead' } },

	{ name: 'Muted Paragraph', 		element: 'p', attributes: { 'class': 'muted' } },
	{ name: 'Warning Paragraph', 	element: 'p', attributes: { 'class': 'text-warning' } },
	{ name: 'Error Paragraph', 		element: 'p', attributes: { 'class': 'text-error' } },
	{ name: 'Info Paragraph', 		element: 'p', attributes: { 'class': 'text-info' } },
	{ name: 'Success Paragraph', 	element: 'p', attributes: { 'class': 'text-success' } },

	{ name: 'Big',				element: 'big' },
	{ name: 'Small',			element: 'small' },

	{ name: 'Unstyled List',	element: 'ul', attributes: { 'class': 'unstyled' } },

	{ name: 'Inline List',	element: 'ul', attributes: { 'class': 'inline' } },

	{ name: 'Rounded Image', element: 'img', attributes: { 'class': 'img-rounded' } },

	{ name: 'Circle Image', element: 'img', attributes: { 'class': 'img-circle' } },

	{ name: 'Polaroid Image', element: 'img', attributes: { 'class': 'img-polaroid' } },

	{ name: 'Image Left', element: 'img', attributes: { 'class': 'pull-left' } },

	{ name: 'Image Right', element: 'img', attributes: { 'class': 'pull-right' } },

	{ name: 'Table', element: 'table', attributes: { 'class': 'table' } },

	{ name: 'Bordered Table', element: 'table', attributes: { 'class': 'table table-bordered' } },

	{ name: 'Striped Table', element: 'table', attributes: { 'class': 'table table-striped' } }
]);

