#!/bin/bash

while true
do
	# Read the URLs for the locations from the locations.txt file
	locations=$(cat sources.txt)
	states=("NY" "CA" "IL" "TX" "AZ" "PA" "FL")

	# Loop through the URLs
	for location in $locations
	do
	# Use curl or wget to download the weather web page for the current location and save it to a file named temp.html
  		curl -o temp.html "$location"
 		for state in ${states[@]}
  		# Set a shell variable to the current date and time
		 do
			 now=$(date +"%Y-%m-%d-%H-%M-%S-$state.html")

  			# Rename the downloaded weather webpage file to the value of the shell variable
	  		mv temp.html "$now"

  			# Call TagSoup to generate a .xhtml file that corresponds to the downloaded .html file
 			 java -jar tagsoup-1.2.1.jar --files "$now"

		  	# Execute the Python script named parser.py
   			python3 parser.py "${now%.html}.xhtml"
			 #deleting state each time after iterating through it
   			states=("${states[@]:1}")
	  		break
		done
	done

	sleep 6h
done


