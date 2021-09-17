# 2. Addons should enforce a minimum YoastSEO version

Date: 2021-09-17

## Status

Accepted

## Context

Our addons sometimes need code that is defined in YoastSEO Free. As a safety measure, addons often check if a particular 
class exists. This makes maintenance more difficult due to coupling. 

## Decision

Addons should not check if a PHP class defined in YoastSEO free exists.
Instead, addons should enforce a minimum free version, and the addon should not load if the prerequisite is not met.

## Consequences

Coupling to specific implementations makes refactoring YoastSEO free code harder. Since YoastSEO free contains the 
framework for our add-ons, we want to keep YoastSEO as flexible and maintainable as possible.

If a minimum YoastSEO version is set, and this addon is only loaded if that requirement is met, we no longer need to 
check if a dependency exists. 

If you need a new dependency from YoastSEO Free, ensure that the specified minimum YoastSEO Free version provides this 
dependency.  
