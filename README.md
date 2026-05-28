# HL 7 Listener Module for GemsTracker 2 

GemsTracker (GEneric Medical Survey Tracker) is a software package for (complex) distribution of questionnaires and forms during clinical research and quality registrations in healthcare.

HL 7 is a set of global standards for the communication of Global data.

This module enables a GemsTracker library project to function as a HL7 2.x Listener. An older low-level communication protocol. 

For using the more modern HL7 FHIR connections use the [GemsTracker Fhir api](https://github.com/GemsTracker/gems-fhir-api)

See [gemstracker library](https://github.com/GemsTracker/gemstracker-library) for the library and issues

# License
GemsTracker and this module are licensed under the New BSD License - see the [LICENSE](LICENSE.txt) file for details

# Development

# Installation

Add the gemstracker/hl7-listener using composer.

# Using the Listener

This module adds the `hl7:listen` command to the command line options of the GemsTracker project:

`php -f bin/console -- hl7:listen` *listener*

The *listener* argument is the name of the HL7 Listener as defined in the config to use.

# Testing

You can test HL7 connections locally using the [HAPI Testpanel](https://sourceforge.net/projects/hl7api/)