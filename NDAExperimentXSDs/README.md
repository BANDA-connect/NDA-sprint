Experiment XML Schema Definitions (XSDs)
========================================

This repository contains XSDs for our experiment model.  At the moment this is limited to task-based experiments.

XSD Organization
----------------

There are 6 schema files:

* NDA.experiment
* NDA.common
* NDA.eeg_experiment
* NDA.eye_experiment
* NDA.fmri_experiment
* NDA.egg_experiment

### NDA.experiment

This is the top level schema, which imports all the other schemas and contains the root element, EXPERIMENT with a choice of experiment type.  To add new experiments one simply needs t import the experiment-specific schema and add this as a choice.

### NDA.common

This contains type definitions that are common across experiment types.  This includes type definitions for the individual experiment elements. Experiment elmements are shared across experiment types, so if one wants to add an element, extend/modify a type definition they only need to so in this file.

### NDA.eeg_experiment

Contains the structure for EEG experiments, including which elements are available and which are required.  Imports NDA.common for type definitions.

### NDA.eye_experiment

Contains the structure for Eye Tracking experiments, including which elements are available and which are required. Imports NDA.common for type definitions.

### NDA.fmri_experiment

Contains the structure for EEG (Electroencephalogram) experiments, including which elements are available and which are required. Imports NDA.common for type definitions.

### NDA.egg_experiment

Contains the structure for EGG (Electroglottography), including which elements are available and which are required. Imports NDA.common for type definitions.

### Examples

Example XML files for each experiment type are provided in /examples directory.