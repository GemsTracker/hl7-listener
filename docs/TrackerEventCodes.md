# Tracker event codes

## Before answer survey

### PrefillAnswers

With this event code you can insert all kinds of GemsTracker info before answering a survey automatically based on the name of a question code in that survey.
Each question code should be prefixed by the type of field it is. The prefixes are case-insensitive, while the rest of the code isn't. 
In this manual we will write the prefixes in upper case.

#### Track fields (prefix TF)
All track fields of the track the token resides in can be added if those fields have a explicit field code.
e.g. a field with the code `diagnosis` will be added if a question code `TFdiagnosis` exists

#### Previous answered questions (CP)
Questions from a previous survey can be added with the question code of the previous survey prefixed with `CP`
The previous token is selected in the following manner:
1. If the survey has a survey code, the nearest (going down in round order) survey with the same code will be selected.
2. Else the nearest survey with the same ID will be selected.

E.g. a previous round has the same survey with a question `bmi`, then the answer will be added if a question code `CPbmi` exists.
The nearest selection does not take into account if the round has been missed or skipped!

#### Respondent information (RD)
Specific respondent information can be added with the following question codes:
- `RDAge`: The current age of the respondent in years
- `RDSex`: The gender of the respondent. Standard GemsTracker currently has `M` for male, `F` for female or `U` for unknown
- `RDBirthDate`: The current birthdate of the respondent in `YYYY-MM-DD` format, e.g. `2000-12-25` 

#### Token information (TO)
Specific token information can be added with the following question codes:
- `TOorganizationCode`: code of the organization of the current token
- `TOroundDescription`: round description of the current token
- `TOroundOrder` => round order of the current token
- `TOtrackName` => track name of the current token track