import { Alert } from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import * as DocumentPicker from 'expo-document-picker';
import * as ImageManipulator from 'expo-image-manipulator';
import type { UploadFile } from '../api/endpoints';

// A raw phone photo is often 3-6 MB. Uploading that over Wi-Fi is slow and was
// timing out ("Network Error"). Resizing to a sane width and re-compressing
// typically brings it under ~300 KB with no visible quality loss for documents
// and profile photos.
const MAX_WIDTH = 1600;
const QUALITY = 0.6;

/** Shrink + compress an image so the upload actually completes. */
async function compressImage(uri: string): Promise<{ uri: string; name: string; type: string }> {
  const result = await ImageManipulator.manipulateAsync(
    uri,
    [{ resize: { width: MAX_WIDTH } }],
    { compress: QUALITY, format: ImageManipulator.SaveFormat.JPEG }
  );
  return { uri: result.uri, name: `upload_${Date.now()}.jpg`, type: 'image/jpeg' };
}

/** Pick a photo from the library and compress it. Returns null if cancelled. */
export async function pickImage(): Promise<UploadFile | null> {
  const perm = await ImagePicker.requestMediaLibraryPermissionsAsync();
  if (!perm.granted) {
    Alert.alert('Permission needed', 'Allow photo access to upload an image.');
    return null;
  }
  const res = await ImagePicker.launchImageLibraryAsync({
    mediaTypes: ['images'],
    quality: 1, // compress ourselves below, after resizing
  });
  if (res.canceled || !res.assets?.length) return null;
  return compressImage(res.assets[0].uri);
}

/**
 * Pick a verification document. Lets the user choose a PDF (or image) from
 * Files, or take/choose a photo. Previously this only opened the photo library,
 * which is why documents could only ever be pictures.
 */
export async function pickDocument(): Promise<UploadFile | null> {
  return new Promise((resolve) => {
    Alert.alert('Upload document', 'Choose where to get the file from', [
      {
        text: 'PDF / File',
        onPress: async () => {
          const res = await DocumentPicker.getDocumentAsync({
            type: ['application/pdf', 'image/*'],
            copyToCacheDirectory: true,
          });
          if (res.canceled || !res.assets?.length) return resolve(null);
          const a = res.assets[0];
          // Images picked from Files still benefit from compression; PDFs go as-is.
          if (a.mimeType?.startsWith('image/')) {
            return resolve(await compressImage(a.uri));
          }
          resolve({
            uri: a.uri,
            name: a.name || `document_${Date.now()}.pdf`,
            type: a.mimeType || 'application/pdf',
          });
        },
      },
      {
        text: 'Photo',
        onPress: async () => resolve(await pickImage()),
      },
      { text: 'Cancel', style: 'cancel', onPress: () => resolve(null) },
    ]);
  });
}
